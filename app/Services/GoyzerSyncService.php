<?php

// 3. app/Services/GoyzerSyncService.php
namespace App\Services;

use App\Models\Property;
use App\Services\GoyzerService;
use Illuminate\Support\Facades\Log;

class GoyzerSyncService
{
    protected $goyzer;

    public function __construct(GoyzerService $goyzer)
    {
        $this->goyzer = $goyzer;
    }

    /**
     * Sync all sales listings
     */
    public function syncSalesListings()
    {
        $listings = $this->goyzer->getSalesListings();
        
        if (!$listings || !isset($listings['Unit'])) {
            return ['success' => false, 'message' => 'No data received'];
        }

        $units = isset($listings['Unit'][0]) ? $listings['Unit'] : [$listings['Unit']];
        $synced = 0;
        $errors = 0;

        foreach ($units as $unit) {
            try {
                $this->syncSaleProperty($unit);
                $synced++;
            } catch (\Exception $e) {
                Log::error('Error syncing sale property: ' . $e->getMessage(), ['unit' => $unit]);
                $errors++;
            }
        }

        return [
            'success' => true,
            'synced' => $synced,
            'errors' => $errors,
            'total' => count($units)
        ];
    }

    /**
     * Sync all rental listings
     */
    public function syncRentalListings()
    {
        $listings = $this->goyzer->getRentalListings();
        
        // Log::info('Rental listings response:', ['data' => $listings]);
        
        if ($listings === null) {
            return ['success' => false, 'message' => 'API request failed'];
        }
        
        // Handle empty array response (no properties available)
        if (is_array($listings) && empty($listings)) {
            return ['success' => true, 'message' => 'No rental properties available in Goyzer account', 'synced' => 0];
        }

        // Handle different response structures
        $units = [];
        if (isset($listings['UnitDTO'])) {
            $units = is_array($listings['UnitDTO']) ? $listings['UnitDTO'] : [$listings['UnitDTO']];
        } elseif (isset($listings['Unit'])) {
            $units = is_array($listings['Unit']) ? $listings['Unit'] : [$listings['Unit']];
        } else {
            return ['success' => false, 'message' => 'No units found in response'];
        }

        if (empty($units)) {
            return ['success' => true, 'message' => 'No properties to sync', 'synced' => 0];
        }

        $synced = 0;
        $errors = 0;

        foreach ($units as $unit) {
            try {
                if (isset($unit['ErrorMessage'])) {
                    Log::warning('Unit has error message:', ['error' => $unit['ErrorMessage']]);
                    continue;
                }
                $this->syncRentalProperty($unit);
                $synced++;
            } catch (\Exception $e) {
                Log::error('Error syncing rental property: ' . $e->getMessage(), ['unit' => $unit]);
                $errors++;
            }
        }

        return [
            'success' => true,
            'synced' => $synced,
            'errors' => $errors,
            'total' => count($units)
        ];
    }

    /**
     * Process updated listings
     */
    public function syncUpdatedListings()
    {
        // Sync updated sales
        $salesUpdated = $this->goyzer->getSalesListingsLastUpdated();
        if ($salesUpdated && isset($salesUpdated['Unit'])) {
            $units = isset($salesUpdated['Unit'][0]) ? $salesUpdated['Unit'] : [$salesUpdated['Unit']];
            foreach ($units as $unit) {
                $this->handleUpdatedUnit($unit, 'sale');
            }
        }

        // Sync updated rentals
        $rentalsUpdated = $this->goyzer->getRentalListingsLastUpdated();
        if ($rentalsUpdated && isset($rentalsUpdated['Unit'])) {
            $units = isset($rentalsUpdated['Unit'][0]) ? $rentalsUpdated['Unit'] : [$rentalsUpdated['Unit']];
            foreach ($units as $unit) {
                $this->handleUpdatedUnit($unit, 'rental');
            }
        }

        return ['success' => true];
    }

    /**
     * Sync individual sale property
     */
    private function syncSaleProperty($unit)
    {
        $data = $this->mapUnitData($unit, 'sale');
        
        Property::updateOrCreate(
            ['unit_pk' => $unit['Unit_PK']],
            $data
        );
    }

    /**
     * Sync individual rental property
     */
    private function syncRentalProperty($unit)
    {
        $data = $this->mapUnitData($unit, 'rental');
        
        Property::updateOrCreate(
            ['unit_pk' => $unit['Unit_PK']],
            $data
        );
    }

    /**
     * Handle updated unit (mark as sold/leased if deleted)
     */
    private function handleUpdatedUnit($unit, $type)
    {
        $property = Property::where('unit_pk', $unit['Unit_PK'])->first();
        
        if (!$property) {
            return;
        }

        // If marked as DELETED, update status
        if (isset($unit['Status']) && $unit['Status'] === 'DELETED') {
            $property->status = $type === 'sale' ? 'sold' : 'leased';
            $property->save();
        } else {
            // Update the property with new data
            $data = $this->mapUnitData($unit, $type);
            $property->update($data);
        }
    }

    /**
     * Map Goyzer unit data to Property model
     */
    private function mapUnitData($unit, $type)
    {
        // Parse images
        $images = [];
        if (isset($unit['Images']['Image'])) {
            $imageData = $unit['Images']['Image'];
            if (!isset($imageData[0])) {
                $imageData = [$imageData];
            }
            foreach ($imageData as $img) {
                $images[] = [
                    'url' => $img['ImageURL'] ?? $img,
                    'title' => $img['ImageTitle'] ?? null
                ];
            }
        }

        // Parse external images
        $externalImages = [];
        if (isset($unit['ExternalImages'])) {
            $externalImages = is_array($unit['ExternalImages']) ? $unit['ExternalImages'] : [$unit['ExternalImages']];
        }

        // Parse facilities
        $facilities = [];
        if (isset($unit['Facilities'])) {
            $facilities = is_array($unit['Facilities']) ? $unit['Facilities'] : [$unit['Facilities']];
        }

        return [
            'unit_pk' => $unit['Unit_PK'],
            'unit_reference_no' => $unit['Unit_Reference_No'] ?? null,
            'type' => $type,
            'property_name' => $unit['Property_Name'] ?? null,
            'marketing_title' => $unit['Marketing_Title'] ?? null,
            'marketing_title_ar' => $unit['Arabic_Title'] ?? null,
            'description' => $unit['Web_Remarks'] ?? null,
            'description_ar' => $unit['Arabic_Description'] ?? null,
            'bedrooms' => $unit['Bedroom_Details'] ?? null,
            'bathrooms' => $unit['No_Of_Bathrooms'] ?? null,
            'rooms' => $unit['No_of_Rooms'] ?? null,
            'unit_type' => $unit['Unit_Type'] ?? null,
            'unit_subtype' => $unit['Sub_Type'] ?? null,
            'unit_model' => $unit['Unit_Model'] ?? null,
            'builtup_area' => $unit['Unit_Builtup_Area'] ?? null,
            'plot_area' => $unit['Unit_Plot_Area'] ?? null,
            'unit_measure' => $unit['Unit_Measure'] ?? 'Sq. Ft',
            'floor_number' => $unit['Floor_Number'] ?? null,
            'selling_price' => $type === 'sale' ? ($unit['Selling_Price'] ?? null) : null,
            'rent_per_annum' => $type === 'rental' ? ($unit['Rent_Per_Annum'] ?? null) : null,
            'maintenance_fee' => $unit['Maintenance_Fee'] ?? null,
            'city' => $unit['City'] ?? null,
            'state' => $unit['State'] ?? null,
            'community' => $unit['Community'] ?? null,
            'sub_community' => $unit['Sub_Community'] ?? null,
            'district' => $unit['District'] ?? null,
            'primary_view' => $unit['Primary_View'] ?? null,
            'secondary_view' => $unit['Secondary_View'] ?? null,
            'images' => $images,
            'floor_plan' => $unit['Floor_Plan'] ?? null,
            'external_images' => $externalImages,
            'listing_agent' => $unit['Listing_Agent'] ?? null,
            'listing_agent_phone' => $unit['Listing_Agent_Phone'] ?? null,
            'listing_agent_email' => $unit['Listing_Agent_Email'] ?? null,
            'branch_name' => $unit['Branch_Name'] ?? null,
            'branch_phone' => $unit['Branch_Phone'] ?? null,
            'company_id' => $unit['Company_ID'] ?? null,
            'company_name' => $unit['Company_Name'] ?? null,
            'company_email' => $unit['Company_Email'] ?? null,
            'company_phone' => $unit['Company_Phone'] ?? null,
            'company_logo' => $unit['Company_Logo'] ?? null,
            'company_registration_number' => $unit['Company_Registration_Number'] ?? null,
            'group_web_url' => $unit['GroupWebURL'] ?? null,
            'map_coordinates' => $unit['Map_Coordinates'] ?? null,
            'facilities' => $facilities,
            'site_info_amenities' => $unit['Site_Info_and_Amenities_Desc'] ?? null,
            'local_area_amenities' => $unit['Local_Area_and_Amenities_Desc'] ?? null,
            'usp' => $unit['USP'] ?? null,
            'freehold_leasehold' => $unit['Freehold_Leasehold'] ?? null,
            'permit_number' => $unit['permit_number'] ?? null,
            'handover_date' => isset($unit['HandOver_Date']) ? date('Y-m-d H:i:s', strtotime($unit['HandOver_Date'])) : null,
            'listing_date' => isset($unit['Listing_Date']) ? date('Y-m-d H:i:s', strtotime($unit['Listing_Date'])) : null,
            'last_updated' => now(),
            'status' => 'active',
            'is_synced' => true,
            'goyzer_data' => $unit,
        ];
    }
}
