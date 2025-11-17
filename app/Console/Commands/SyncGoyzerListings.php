<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoyzerService;
use Illuminate\Support\Facades\Cache;

class SyncGoyzerListings extends Command
{
    protected $signature = 'goyzer:sync {--type=all : Type of listings to sync (sales|rentals|all)} {--bedrooms= : Filter by number of bedrooms} {--min-price= : Minimum price} {--max-price= : Maximum price} {--city= : Filter by city} {--community= : Filter by community}';
    protected $description = 'Sync property listings from Goyzer API';

    protected $goyzer;

    public function __construct(GoyzerService $goyzer)
    {
        parent::__construct();
        $this->goyzer = $goyzer;
    }

    public function handle()
    {
        $type = $this->option('type');

        $this->info('Starting Goyzer sync...');

        if ($type === 'sales' || $type === 'all') {
            $this->syncSalesListings();
        }

        if ($type === 'rentals' || $type === 'all') {
            $this->syncRentalListings();
        }

        $this->info('Sync completed successfully!');
    }

    private function syncSalesListings()
    {
        $this->info('Syncing sales listings...');
        
        // Build parameters from options
        $params = $this->buildParams();
        
        // Get sales listings with filters
        $listings = $this->goyzer->getSalesListings($params);
        
        if ($listings) {
            Cache::put('goyzer_sales_all', $listings, now()->addHours(24));
            $this->info('Sales listings cached successfully');
        } else {
            $this->error('Failed to fetch sales listings');
        }

        // Sync updated listings
        $updated = $this->goyzer->getSalesListingsLastUpdated();
        if ($updated) {
            Cache::put('goyzer_sales_updated', $updated, now()->addHours(24));
            $this->info('Updated sales listings cached successfully');
        }
    }

    private function syncRentalListings()
    {
        $this->info('Syncing rental listings...');
        
        // Build parameters from options
        $params = $this->buildParams();
        
        // Get rental listings with filters
        $listings = $this->goyzer->getRentalListings($params);
        
        if ($listings) {
            Cache::put('goyzer_rentals_all', $listings, now()->addHours(24));
            $this->info('Rental listings cached successfully');
        } else {
            $this->error('Failed to fetch rental listings');
        }

        // Sync updated listings
        $updated = $this->goyzer->getRentalListingsLastUpdated();
        if ($updated) {
            Cache::put('goyzer_rentals_updated', $updated, now()->addHours(24));
            $this->info('Updated rental listings cached successfully');
        }
    }

    private function buildParams()
    {
        $params = [];
        
        if ($this->option('bedrooms')) {
            $params['Bedrooms'] = $this->option('bedrooms');
        }
        
        if ($this->option('min-price')) {
            $params['StartPriceRange'] = $this->option('min-price');
        }
        
        if ($this->option('max-price')) {
            $params['EndPriceRange'] = $this->option('max-price');
        }
        
        return $params;
    }
}