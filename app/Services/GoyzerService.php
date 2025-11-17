<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoyzerService
{
    protected $baseUrl;
    protected $accessCode;
    protected $groupCode;

    public function __construct()
    {
        $this->baseUrl = config('services.goyzer.base_url', 'http://webapi.goyzer.com/Company.asmx');
        $this->accessCode = config('services.goyzer.access_code');
        $this->groupCode = config('services.goyzer.group_code');
    }

    /**
     * Get all sales listings
     */
    public function getSalesListings($params = [])
    {
        $defaultParams = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'Bedrooms' => 0,
            'StartPriceRange' => 0,
            'EndPriceRange' => 999999999,
            'CategoryID' => 0,
            'SpecialProjects' => 0,
            'CountryID' => 1,
            'StateID' => 0,
            'CommunityID' => 0,
            'DistrictID' => 0,
            'FloorAreaMin' => 0,
            'FloorAreaMax' => 999999,
            'UnitCategory' => 0,
            'PropertyID' => 0,
            'UnitID' => 0,
            'BedroomsMax' => 10,
            'ReadyNow' => 0,
            'PageIndex' => 1,
        ];

        $params = array_merge($defaultParams, $params);

        return $this->makeRequest('SalesListings', $params);
    }

    /**
     * Get all rental listings
     */
    public function getRentalListings($params = [])
    {
        $defaultParams = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'PropertyType' => 'Residential',
            'Bedrooms' => 0,
            'Unit_pk' => 0,
            'State_pk' => '',
            'Community_pk' => '',
            'StartPriceRange' => 0,
            'EndPriceRange' => 999999999,
            'categoryID' => 0,
            'CountryID' => 1,
            'StateID' => 0,
            'CommunityID' => 0,
            'FloorAreaMin' => 0,
            'FloorAreaMax' => 999999,
            'UnitCategory' => 0,
            'UnitID' => 0,
            'BedroomsMax' => 10,
            'PropertyID' => 0,
            'ReadyNow' => 0,
            'PageIndex' => 1,
        ];

        $params = array_merge($defaultParams, $params);

        return $this->makeRequest('RentListings', $params);
    }

    /**
     * Get sales listings updated in last 24 hours
     */
    public function getSalesListingsLastUpdated()
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
        ];

        return $this->makeRequest('GetUpdatedUnitsForSales', $params);
    }

    /**
     * Get rental listings updated in last 24 hours
     */
    public function getRentalListingsLastUpdated()
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
        ];

        return $this->makeRequest('GetUpdatedUnitsForLease', $params);
    }

    /**
     * Make HTTP request to Goyzer API
     */
    protected function makeRequest($method, $params)
    {
        try {
            $response = Http::timeout(120)
                ->asForm()
                ->post("{$this->baseUrl}/{$method}", $params);

            if ($response->successful()) {
                return $this->parseXmlResponse($response->body());
            }

            Log::error("Goyzer API request failed", [
                'method' => $method,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("Goyzer API exception", [
                'method' => $method,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Parse XML response to array
     */
    protected function parseXmlResponse($xmlString)
    {
        try {
            $xml = simplexml_load_string($xmlString);
            
            if ($xml === false) {
                return null;
            }

            return json_decode(json_encode($xml), true);
        } catch (\Exception $e) {
            Log::error("XML parsing error", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get cached sales listings
     */
    public function getCachedSalesListings()
    {
        return Cache::get('goyzer_sales_all');
    }

    /**
     * Get cached rental listings
     */
    public function getCachedRentalListings()
    {
        return Cache::get('goyzer_rentals_all');
    }

    /**
     * Get bedrooms data
     */
    public function getBedrooms($bedroomId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'BedroomID' => $bedroomId,
        ];

        return $this->makeRequest('GetBedrooms', $params);
    }

    /**
     * Get states data
     */
    public function getStates($countryId = '', $stateId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'CountryID' => $countryId,
            'StateID' => $stateId,
        ];

        return $this->makeRequest('GetStates', $params);
    }

    /**
     * Get communities data
     */
    public function getCommunities($districtId = '', $communityId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'DistrictID' => $districtId,
            'CommunityID' => $communityId,
        ];

        return $this->makeRequest('GetCommunities', $params);
    }

    /**
     * Get agents data
     */
    public function getAgents($agentId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'AgentID' => $agentId,
        ];

        return $this->makeRequest('GetAgents', $params);
    }

    /**
     * Get amenities data
     */
    public function getAmenities($amenityId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'AmenityID' => $amenityId,
        ];

        return $this->makeRequest('GetAmenities', $params);
    }

    /**
     * Get budget data
     */
    public function getBudget($budgetId = '', $unitId = '', $clientRequirementTypeId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'BudgetID' => $budgetId,
            'UnitID' => $unitId,
            'ClientRequirementTypeID' => $clientRequirementTypeId,
        ];

        return $this->makeRequest('GetBudget', $params);
    }

    /**
     * Get budget by country data
     */
    public function getBudgetByCountry($budgetId = '', $unitId = '', $clientRequirementTypeId = '', $countryId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'BudgetID' => $budgetId,
            'UnitID' => $unitId,
            'ClientRequirementTypeID' => $clientRequirementTypeId,
            'CountryID' => $countryId,
        ];

        return $this->makeRequest('GetBudgetByCountry', $params);
    }

    /**
     * Get cities data
     */
    public function getCities($stateId = '', $cityId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'StateID' => $stateId,
            'cityID' => $cityId,
        ];

        return $this->makeRequest('GetCities', $params);
    }

    /**
     * Get country data
     */
    public function getCountry($countryId = '', $countryName = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'CountryID' => $countryId,
            'CountryName' => $countryName,
        ];

        return $this->makeRequest('GetCountry', $params);
    }

    /**
     * Get districts data
     */
    public function getDistricts($cityId = '', $districtId = '', $stateId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'CityID' => $cityId,
            'DistrictID' => $districtId,
            'StateID' => $stateId,
        ];

        return $this->makeRequest('GetDistricts', $params);
    }

    /**
     * Get sub-community data
     */
    public function getSubCommunity($communityId = '', $subCommunityId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'CommunityID' => $communityId,
            'SubCommunityID' => $subCommunityId,
        ];

        return $this->makeRequest('GetSubCommunity', $params);
    }

    /**
     * Get nationality data
     */
    public function getNationality($nationalityId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'NationalityID' => $nationalityId,
        ];

        return $this->makeRequest('GetNationality', $params);
    }

    /**
     * Get title data
     */
    public function getTitle($titleId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'TitleID' => $titleId,
        ];

        return $this->makeRequest('GetTitle', $params);
    }

    /**
     * Get requirement type data
     */
    public function getRequirementType($requirementTypeId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'RequirementTypeID' => $requirementTypeId,
        ];

        return $this->makeRequest('GetRequirementType', $params);
    }

    /**
     * Get contact methods data
     */
    public function getContactMethods($contactMethodId = '', $contactMethodName = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'ContactMethodID' => $contactMethodId,
            'ContactMethodName' => $contactMethodName,
        ];

        return $this->makeRequest('GetContactMethods', $params);
    }

    /**
     * Get unit category data
     */
    public function getUnitCategory($categoryId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'CategoryID' => $categoryId,
        ];

        return $this->makeRequest('GetUnitCategory', $params);
    }

    /**
     * Get unit sub type data
     */
    public function getUnitSubType($unitSubTypeId = '', $unitSubTypeName = '', $unitTypeId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'UnitSubTypeID' => $unitSubTypeId,
            'UnitSubTypeName' => $unitSubTypeName,
            'UnitTypeID' => $unitTypeId,
        ];

        return $this->makeRequest('GetUnitSubType', $params);
    }

    /**
     * Get unit view data
     */
    public function getUnitView($unitViewId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'unitviewID' => $unitViewId,
        ];

        return $this->makeRequest('GetUnitView', $params);
    }

    /**
     * Get facility data
     */
    public function getFacility($facilityId = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'FacilityID' => $facilityId,
        ];

        return $this->makeRequest('GetFacility', $params);
    }

    /**
     * Get properties data
     */
    public function getProperties($propertyId = '', $countryId = '', $pageIndex = '')
    {
        $params = [
            'AccessCode' => $this->accessCode,
            'GroupCode' => $this->groupCode,
            'PropertyID' => $propertyId,
            'CountryID' => $countryId,
            'PageIndex' => $pageIndex,
        ];

        return $this->makeRequest('GetProperties', $params);
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        // Use GetBedrooms as it's simpler and should return data
        $response = $this->getBedrooms();
        
        return [
            'success' => $response !== null,
            'data' => $response
        ];
    }
}