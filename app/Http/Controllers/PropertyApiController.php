<?php

// app/Http/Controllers/Api/PropertyApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Services\GoyzerService;
use App\Services\GoyzerSyncService;

class PropertyApiController extends Controller
{
    protected $goyzer;
    protected $goyzerSync;

    public function __construct(GoyzerService $goyzer, GoyzerSyncService $goyzerSync)
    {
        $this->goyzer = $goyzer;
        $this->goyzerSync = $goyzerSync;
    }

    /**
     * Get all properties with filters
     * GET /api/properties
     */
    public function index(Request $request)
    {
        $query = Property::query();

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active'); // Default to active
        }

        // Filter by bedrooms
        if ($request->has('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        // Filter by price range (for sales)
        if ($request->has('min_price')) {
            $query->where('selling_price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('selling_price', '<=', $request->max_price);
        }

        // Filter by rent range (for rentals)
        if ($request->has('min_rent')) {
            $query->where('rent_per_annum', '>=', $request->min_rent);
        }
        if ($request->has('max_rent')) {
            $query->where('rent_per_annum', '<=', $request->max_rent);
        }

        // Filter by location
        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->has('community')) {
            $query->where('community', 'like', '%' . $request->community . '%');
        }

        // Search in title and description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marketing_title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('property_name', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $properties = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $properties->items(),
            'pagination' => [
                'total' => $properties->total(),
                'per_page' => $properties->perPage(),
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'from' => $properties->firstItem(),
                'to' => $properties->lastItem(),
            ]
        ]);
    }

    /**
     * Get single property by ID or unit_pk
     * GET /api/properties/{id}
     */
    public function show($id)
    {
        // Try to find by ID first, then by unit_pk
        $property = Property::find($id) ?? Property::where('unit_pk', $id)->first();

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $property
        ]);
    }

    /**
     * Get properties for sale
     * GET /api/properties/sales
     */
    public function sales(Request $request)
    {
        $request->merge(['type' => 'sale']);
        return $this->index($request);
    }

    /**
     * Get properties for rent
     * GET /api/properties/rentals
     */
    public function rentals(Request $request)
    {
        $request->merge(['type' => 'rental']);
        return $this->index($request);
    }

    /**
     * Get property statistics
     * GET /api/properties/stats
     */
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => Property::count(),
                'for_sale' => Property::where('type', 'sale')->where('status', 'active')->count(),
                'for_rent' => Property::where('type', 'rental')->where('status', 'active')->count(),
                'sold' => Property::where('status', 'sold')->count(),
                'leased' => Property::where('status', 'leased')->count(),
                'avg_sale_price' => Property::where('type', 'sale')->where('status', 'active')->avg('selling_price'),
                'avg_rent_price' => Property::where('type', 'rental')->where('status', 'active')->avg('rent_per_annum'),
                'by_bedrooms' => Property::where('status', 'active')
                    ->selectRaw('bedrooms, COUNT(*) as count')
                    ->groupBy('bedrooms')
                    ->orderBy('bedrooms')
                    ->get(),
                'by_city' => Property::where('status', 'active')
                    ->selectRaw('city, COUNT(*) as count')
                    ->groupBy('city')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get(),
            ]
        ]);
    }

    /**
     * Get unique filter options
     * GET /api/properties/filters
     */
    public function filters()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cities' => Property::where('status', 'active')->distinct()->pluck('city')->filter()->values(),
                'communities' => Property::where('status', 'active')->distinct()->pluck('community')->filter()->values(),
                'bedrooms' => Property::where('status', 'active')->distinct()->pluck('bedrooms')->filter()->sort()->values(),
                'unit_types' => Property::where('status', 'active')->distinct()->pluck('unit_type')->filter()->values(),
                'price_range' => [
                    'min' => Property::where('type', 'sale')->where('status', 'active')->min('selling_price'),
                    'max' => Property::where('type', 'sale')->where('status', 'active')->max('selling_price'),
                ],
                'rent_range' => [
                    'min' => Property::where('type', 'rental')->where('status', 'active')->min('rent_per_annum'),
                    'max' => Property::where('type', 'rental')->where('status', 'active')->max('rent_per_annum'),
                ],
            ]
        ]);
    }

    /**
     * Sync properties from Goyzer
     * POST /api/properties/sync
     */
    public function sync(Request $request)
    {
        $request->validate([
            'type' => 'sometimes|in:sale,rental,all'
        ]);

        $type = $request->get('type', 'all');

        try {
            $results = [];

            if ($type === 'sale' || $type === 'all') {
                $results['sales'] = $this->goyzerSync->syncSalesListings();
            }

            if ($type === 'rental' || $type === 'all') {
                $results['rentals'] = $this->goyzerSync->syncRentalListings();
            }

            return response()->json([
                'success' => true,
                'message' => 'Sync completed successfully',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch directly from Goyzer API (bypass database)
     * POST /api/goyzer/fetch
     */
    public function fetchFromGoyzer(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sale,rental',
            'filters' => 'sometimes|array'
        ]);

        try {
            $filters = $request->get('filters', []);

            if ($request->type === 'sale') {
                $data = $this->goyzer->getSalesListings($filters);
            } else {
                $data = $this->goyzer->getRentalListings($filters);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch from Goyzer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search properties
     * GET /api/properties/search
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = Property::where('status', 'active')
            ->where(function($q) use ($request) {
                $search = $request->q;
                $q->where('marketing_title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('property_name', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('community', 'like', '%' . $search . '%');
            })
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $query,
            'count' => $query->count()
        ]);
    }
}