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
        
        $this->info('Refreshing cache...');
        
        Cache::remember('goyzer_agents', 3600, fn() => $goyzerService->getAgents());
        Cache::remember('goyzer_amenities', 3600, fn() => $goyzerService->getAmenities());
        Cache::remember('goyzer_bedrooms', 3600, fn() => $goyzerService->getBedrooms());
        Cache::remember('goyzer_budgets', 3600, fn() => $goyzerService->getBudget());
        Cache::remember('goyzer_budgets_by_country', 3600, fn() => $goyzerService->getBudgetByCountry());
        Cache::remember('goyzer_cities', 3600, fn() => $goyzerService->getCities());
        Cache::remember('goyzer_communities', 3600, fn() => $goyzerService->getCommunities());
        Cache::remember('goyzer_contact_methods', 3600, fn() => $goyzerService->getContactMethods());
        Cache::remember('goyzer_countries', 3600, fn() => $goyzerService->getCountry());
        Cache::remember('goyzer_districts', 3600, fn() => $goyzerService->getDistricts());
        Cache::remember('goyzer_facilities', 3600, fn() => $goyzerService->getFacility());
        Cache::remember('goyzer_nationalities', 3600, fn() => $goyzerService->getNationality());
        Cache::remember('goyzer_requirement_types', 3600, fn() => $goyzerService->getRequirementType());
        Cache::remember('goyzer_states', 3600, fn() => $goyzerService->getStates());
        Cache::remember('goyzer_sub_communities', 3600, fn() => $goyzerService->getSubCommunity());
        Cache::remember('goyzer_titles', 3600, fn() => $goyzerService->getTitle());
        Cache::remember('goyzer_unit_categories', 3600, fn() => $goyzerService->getUnitCategory());
        Cache::remember('goyzer_unit_sub_types', 3600, fn() => $goyzerService->getUnitSubType());
        Cache::remember('goyzer_unit_views', 3600, fn() => $goyzerService->getUnitView());
        
        $this->info('Goyzer cache synced successfully!');
        
        return Command::SUCCESS;
    }
}
