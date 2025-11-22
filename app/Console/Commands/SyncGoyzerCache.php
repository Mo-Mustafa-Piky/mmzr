<?php

namespace App\Console\Commands;

use App\Services\GoyzerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncGoyzerCache extends Command
{
    protected $signature = 'goyzer:sync-cache';
    protected $description = 'Clear and refresh Goyzer cache data';

    public function handle(GoyzerService $goyzerService): int
    {
        $this->info('Clearing Goyzer cache...');
        
        Cache::forget('goyzer_agents');
        Cache::forget('goyzer_amenities');
        Cache::forget('goyzer_bedrooms');
        Cache::forget('goyzer_budgets');
        Cache::forget('goyzer_budgets_by_country');
        Cache::forget('goyzer_cities');
        Cache::forget('goyzer_communities');
        Cache::forget('goyzer_contact_methods');
        Cache::forget('goyzer_countries');
        Cache::forget('goyzer_districts');
        Cache::forget('goyzer_facilities');
        Cache::forget('goyzer_nationalities');
        Cache::forget('goyzer_requirement_types');
        Cache::forget('goyzer_states');
        Cache::forget('goyzer_sub_communities');
        Cache::forget('goyzer_titles');
        Cache::forget('goyzer_unit_categories');
        Cache::forget('goyzer_unit_sub_types');
        Cache::forget('goyzer_unit_views');
        Cache::forget('goyzer_updated_units_sales');
        Cache::forget('goyzer_updated_units_rentals');
        Cache::forget('goyzer_updated_projects');
        Cache::forget('goyzer_sales_listings');
        Cache::forget('goyzer_properties');
        Cache::forget('goyzer_rental_listings');
        Cache::forget('goyzer_sold_listings');
        
        $this->info('Refreshing cache...');
        
        Cache::rememberForever('goyzer_agents', fn() => $goyzerService->getAgents());
        Cache::rememberForever('goyzer_amenities', fn() => $goyzerService->getAmenities());
        Cache::rememberForever('goyzer_bedrooms', fn() => $goyzerService->getBedrooms());
        Cache::rememberForever('goyzer_budgets', fn() => $goyzerService->getBudget());
        Cache::rememberForever('goyzer_budgets_by_country', fn() => $goyzerService->getBudgetByCountry());
        Cache::rememberForever('goyzer_cities', fn() => $goyzerService->getCities());
        Cache::rememberForever('goyzer_communities', fn() => $goyzerService->getCommunities());
        Cache::rememberForever('goyzer_contact_methods', fn() => $goyzerService->getContactMethods());
        Cache::rememberForever('goyzer_countries', fn() => $goyzerService->getCountry());
        Cache::rememberForever('goyzer_districts', fn() => $goyzerService->getDistricts());
        Cache::rememberForever('goyzer_facilities', fn() => $goyzerService->getFacility());
        Cache::rememberForever('goyzer_nationalities', fn() => $goyzerService->getNationality());
        Cache::rememberForever('goyzer_requirement_types', fn() => $goyzerService->getRequirementType());
        Cache::rememberForever('goyzer_states', fn() => $goyzerService->getStates());
        Cache::rememberForever('goyzer_sub_communities', fn() => $goyzerService->getSubCommunity());
        Cache::rememberForever('goyzer_titles', fn() => $goyzerService->getTitle());
        Cache::rememberForever('goyzer_unit_categories', fn() => $goyzerService->getUnitCategory());
        Cache::rememberForever('goyzer_unit_sub_types', fn() => $goyzerService->getUnitSubType());
        Cache::rememberForever('goyzer_unit_views', fn() => $goyzerService->getUnitView());
        Cache::rememberForever('goyzer_updated_units_sales', fn() => $goyzerService->getSalesListingsLastUpdated());
        Cache::rememberForever('goyzer_updated_units_rentals', fn() => $goyzerService->getRentalListingsLastUpdated());
        Cache::rememberForever('goyzer_updated_projects', fn() => $goyzerService->getProjectsLastUpdated());
        Cache::rememberForever('goyzer_sales_listings', fn() => $goyzerService->getSalesListings());
        Cache::rememberForever('goyzer_properties', fn() => $goyzerService->getProperties());
        Cache::rememberForever('goyzer_rental_listings', fn() => $goyzerService->getRentalListings());
        Cache::rememberForever('goyzer_sold_listings', fn() => $goyzerService->getSoldListings());
        
        $this->info('Goyzer cache synced successfully!');
        
        return Command::SUCCESS;
    }
}
