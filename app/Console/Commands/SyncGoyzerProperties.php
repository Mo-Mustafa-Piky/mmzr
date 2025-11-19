<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoyzerService;
use Illuminate\Support\Facades\DB;

class SyncGoyzerProperties extends Command
{
    protected $signature = 'goyzer:sync-properties';
    protected $description = 'Sync properties from Goyzer API to database';

    public function handle()
    {
        $this->info('Fetching properties from Goyzer API...');
        
        $goyzer = app(GoyzerService::class);
        $result = $goyzer->getProperties();
        
        if (!$result || !isset($result['GetPropertiesData'])) {
            $this->error('Failed to fetch data from Goyzer API');
            return 1;
        }

        $properties = is_array($result['GetPropertiesData']) && isset($result['GetPropertiesData'][0]) 
            ? $result['GetPropertiesData'] 
            : [$result['GetPropertiesData']];

        $this->info('Syncing ' . count($properties) . ' properties...');

        DB::table('goyzer_properties')->truncate();
        
        foreach ($properties as $property) {
            DB::table('goyzer_properties')->insert([
                'data' => json_encode($property),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info('Sync completed successfully!');
        return 0;
    }
}
