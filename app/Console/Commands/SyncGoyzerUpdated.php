<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoyzerSyncService;

class SyncGoyzerUpdated extends Command
{
    protected $signature = 'goyzer:sync-updated';
    protected $description = 'Sync updated property listings from Goyzer API (last 24 hours)';

    protected $syncService;

    public function __construct(GoyzerSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $this->info('Syncing updated Goyzer listings...');

        $result = $this->syncService->syncUpdatedListings();

        if ($result['success']) {
            $this->info('✅ Updated listings synced successfully!');
        } else {
            $this->error('❌ Failed to sync updated listings');
        }

        return $result['success'] ? 0 : 1;
    }
}