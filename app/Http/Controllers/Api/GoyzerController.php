<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoyzerService;
use App\Services\GoyzerSyncService;
use App\Models\Property;
use Illuminate\Http\Request;

class GoyzerController extends Controller
{
    protected $goyzer;
    protected $syncService;

    public function __construct(GoyzerService $goyzer, GoyzerSyncService $syncService)
    {
        $this->goyzer = $goyzer;
        $this->syncService = $syncService;
    }

    /**
     * Test Goyzer API connection
     */
    public function testConnection()
    {
        $result = $this->goyzer->testConnection();
        
        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'Connection successful' : 'Connection failed',
            'data' => $result['data']
        ]);
    }

    /**
     * Sync all listings
     */
    public function syncAll()
    {
        $salesResult = $this->syncService->syncSalesListings();
        $rentalResult = $this->syncService->syncRentalListings();
        
        return response()->json([
            'success' => true,
            'sales' => $salesResult,
            'rentals' => $rentalResult
        ]);
    }

    /**
     * Sync sales listings only
     */
    public function syncSales()
    {
        $result = $this->syncService->syncSalesListings();
        
        return response()->json($result);
    }

    /**
     * Sync rental listings only
     */
    public function syncRentals()
    {
        $result = $this->syncService->syncRentalListings();
        
        return response()->json($result);
    }

    /**
     * Sync updated listings
     */
    public function syncUpdated()
    {
        $result = $this->syncService->syncUpdatedListings();
        
        return response()->json($result);
    }

    /**
     * Get properties with filters
     */
    public function getProperties(Request $request)
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
            $query->where('status', 'active');
        }

        // Filter by bedrooms
        if ($request->has('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            if ($request->type === 'sale') {
                $query->whereBetween('selling_price', [$request->min_price, $request->max_price]);
            } else {
                $query->whereBetween('rent_per_annum', [$request->min_price, $request->max_price]);
            }
        }

        // Filter by location
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('community')) {
            $query->where('community', $request->community);
        }

        // Filter by unit type
        if ($request->has('unit_type')) {
            $query->where('unit_type', $request->unit_type);
        }

        $properties = $query->paginate($request->get('per_page', 15));

        return response()->json($properties);
    }

    /**
     * Get single property
     */
    public function getProperty($unitPk)
    {
        $property = Property::where('unit_pk', $unitPk)->first();

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        return response()->json($property);
    }

    /**
     * Get sync statistics
     */
    public function getSyncStats()
    {
        $stats = [
            'total_properties' => Property::count(),
            'active_properties' => Property::where('status', 'active')->count(),
            'sales_properties' => Property::where('type', 'sale')->where('status', 'active')->count(),
            'rental_properties' => Property::where('type', 'rental')->where('status', 'active')->count(),
            'sold_properties' => Property::where('status', 'sold')->count(),
            'leased_properties' => Property::where('status', 'leased')->count(),
            'last_sync' => Property::max('last_updated'),
            'synced_properties' => Property::where('is_synced', true)->count(),
        ];

        return response()->json($stats);
    }
    
    /**
     * Create test properties for demonstration
     */
    public function createTestData()
    {
        $testProperties = [
            [
                'unit_pk' => 'TEST001',
                'type' => 'rental',
                'property_name' => 'Marina Heights',
                'marketing_title' => 'Luxury 2BR Apartment in Dubai Marina',
                'description' => 'Beautiful 2-bedroom apartment with sea view',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'unit_type' => 'Apartment',
                'builtup_area' => 1200.00,
                'rent_per_annum' => 85000.00,
                'city' => 'Dubai',
                'community' => 'Dubai Marina',
                'status' => 'active',
                'is_synced' => false,
                'last_updated' => now(),
            ],
            [
                'unit_pk' => 'TEST002',
                'type' => 'sale',
                'property_name' => 'Downtown Views',
                'marketing_title' => 'Stunning 3BR Penthouse for Sale',
                'description' => 'Luxurious penthouse with panoramic city views',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'unit_type' => 'Penthouse',
                'builtup_area' => 2500.00,
                'selling_price' => 2500000.00,
                'city' => 'Dubai',
                'community' => 'Downtown Dubai',
                'status' => 'active',
                'is_synced' => false,
                'last_updated' => now(),
            ]
        ];
        
        foreach ($testProperties as $property) {
            Property::updateOrCreate(
                ['unit_pk' => $property['unit_pk']],
                $property
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Test properties created successfully',
            'created' => count($testProperties)
        ]);
    }
    
    /**
     * Get reference data from Goyzer
     */
    public function getReferenceData()
    {
        $bedrooms = $this->goyzer->getBedrooms();
        $states = $this->goyzer->getStates();
        
        return response()->json([
            'bedrooms' => $bedrooms,
            'states' => $states
        ]);
    }

    /**
     * Get agents from Goyzer
     */
    public function getAgents(Request $request)
    {
        $agentId = $request->get('agent_id', '');
        $result = $this->goyzer->getAgents($agentId);
        
        return response()->json($result);
    }

    /**
     * Get amenities from Goyzer
     */
    public function getAmenities(Request $request)
    {
        $amenityId = $request->get('amenity_id', '');
        $result = $this->goyzer->getAmenities($amenityId);
        
        return response()->json($result);
    }

    /**
     * Get bedrooms from Goyzer
     */
    public function getBedrooms(Request $request)
    {
        $bedroomId = $request->get('bedroom_id', '');
        $result = $this->goyzer->getBedrooms($bedroomId);
        
        return response()->json($result);
    }

    /**
     * Get budget from Goyzer
     */
    public function getBudget(Request $request)
    {
        $budgetId = $request->get('budget_id', '');
        $unitId = $request->get('unit_id', '');
        $clientRequirementTypeId = $request->get('client_requirement_type_id', '');
        $result = $this->goyzer->getBudget($budgetId, $unitId, $clientRequirementTypeId);
        
        return response()->json($result);
    }

    /**
     * Get budget by country from Goyzer
     */
    public function getBudgetByCountry(Request $request)
    {
        $budgetId = $request->get('budget_id', '');
        $unitId = $request->get('unit_id', '');
        $clientRequirementTypeId = $request->get('client_requirement_type_id', '');
        $countryId = $request->get('country_id', '');
        $result = $this->goyzer->getBudgetByCountry($budgetId, $unitId, $clientRequirementTypeId, $countryId);
        
        return response()->json($result);
    }

    /**
     * Get cities from Goyzer
     */
    public function getCities(Request $request)
    {
        $stateId = $request->get('state_id', '');
        $cityId = $request->get('city_id', '');
        $result = $this->goyzer->getCities($stateId, $cityId);
        
        return response()->json($result);
    }

    /**
     * Get states from Goyzer
     */
    public function getStates(Request $request)
    {
        $countryId = $request->get('country_id', '');
        $stateId = $request->get('state_id', '');
        $result = $this->goyzer->getStates($countryId, $stateId);
        
        return response()->json($result);
    }

    /**
     * Get countries from Goyzer
     */
    public function getCountries(Request $request)
    {
        $countryId = $request->get('country_id', '');
        $countryName = $request->get('country_name', '');
        $result = $this->goyzer->getCountry($countryId, $countryName);
        
        return response()->json($result);
    }

    /**
     * Get districts from Goyzer
     */
    public function getDistricts(Request $request)
    {
        $cityId = $request->get('city_id', '');
        $districtId = $request->get('district_id', '');
        $stateId = $request->get('state_id', '');
        $result = $this->goyzer->getDistricts($cityId, $districtId, $stateId);
        
        return response()->json($result);
    }

    /**
     * Get communities from Goyzer
     */
    public function getCommunities(Request $request)
    {
        $districtId = $request->get('district_id', '');
        $communityId = $request->get('community_id', '');
        $result = $this->goyzer->getCommunities($districtId, $communityId);
        
        return response()->json($result);
    }

    /**
     * Get sub-communities from Goyzer
     */
    public function getSubCommunities(Request $request)
    {
        $communityId = $request->get('community_id', '');
        $subCommunityId = $request->get('sub_community_id', '');
        $result = $this->goyzer->getSubCommunity($communityId, $subCommunityId);
        
        return response()->json($result);
    }

    /**
     * Get nationalities from Goyzer
     */
    public function getNationalities(Request $request)
    {
        $nationalityId = $request->get('nationality_id', '');
        $result = $this->goyzer->getNationality($nationalityId);
        
        return response()->json($result);
    }

    /**
     * Get titles from Goyzer
     */
    public function getTitles(Request $request)
    {
        $titleId = $request->get('title_id', '');
        $result = $this->goyzer->getTitle($titleId);
        
        return response()->json($result);
    }

    /**
     * Get requirement types from Goyzer
     */
    public function getRequirementTypes(Request $request)
    {
        $requirementTypeId = $request->get('requirement_type_id', '');
        $result = $this->goyzer->getRequirementType($requirementTypeId);
        
        return response()->json($result);
    }

    /**
     * Get contact methods from Goyzer
     */
    public function getContactMethods(Request $request)
    {
        $contactMethodId = $request->get('contact_method_id', '');
        $contactMethodName = $request->get('contact_method_name', '');
        $result = $this->goyzer->getContactMethods($contactMethodId, $contactMethodName);
        
        return response()->json($result);
    }

    /**
     * Get unit categories from Goyzer
     */
    public function getUnitCategories(Request $request)
    {
        $categoryId = $request->get('category_id', '');
        $result = $this->goyzer->getUnitCategory($categoryId);
        
        return response()->json($result);
    }

    /**
     * Get unit sub types from Goyzer
     */
    public function getUnitSubTypes(Request $request)
    {
        $unitSubTypeId = $request->get('unit_sub_type_id', '');
        $unitSubTypeName = $request->get('unit_sub_type_name', '');
        $unitTypeId = $request->get('unit_type_id', '');
        $result = $this->goyzer->getUnitSubType($unitSubTypeId, $unitSubTypeName, $unitTypeId);
        
        return response()->json($result);
    }

    /**
     * Get unit views from Goyzer
     */
    public function getUnitViews(Request $request)
    {
        $unitViewId = $request->get('unit_view_id', '');
        $result = $this->goyzer->getUnitView($unitViewId);
        
        return response()->json($result);
    }

    /**
     * Get facilities from Goyzer
     */
    public function getFacilities(Request $request)
    {
        $facilityId = $request->get('facility_id', '');
        $result = $this->goyzer->getFacility($facilityId);
        
        return response()->json($result);
    }

    /**
     * Get properties from Goyzer
     */
    public function getGoyzerProperties(Request $request)
    {
        $propertyId = $request->get('property_id', '');
        $countryId = $request->get('country_id', '');
        $pageIndex = $request->get('page_index', '');
        $result = $this->goyzer->getProperties($propertyId, $countryId, $pageIndex);
        
        return response()->json($result);
    }
}