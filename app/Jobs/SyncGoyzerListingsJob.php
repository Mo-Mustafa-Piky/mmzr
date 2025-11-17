<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\GoyzerSyncService;
use Illuminate\Support\Facades\Log;

class SyncGoyzerListingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;

    public function __construct($type = 'all')
    {
        $this->type = $type;
    }

    public function handle(GoyzerSyncService $syncService)
    {
        Log::info("Starting Goyzer sync job for type: {$this->type}");

        try {
            switch ($this->type) {
                case 'sales':
                    $result = $syncService->syncSalesListings();
                    break;
                case 'rentals':
                    $result = $syncService->syncRentalListings();
                    break;
                case 'updated':
                    $result = $syncService->syncUpdatedListings();
                    break;
                case 'all':
                default:
                    $salesResult = $syncService->syncSalesListings();
                    $rentalResult = $syncService->syncRentalListings();
                    $result = [
                        'success' => $salesResult['success'] && $rentalResult['success'],
                        'sales' => $salesResult,
                        'rentals' => $rentalResult
                    ];
                    break;
            }

            Log::info("Goyzer sync job completed", ['type' => $this->type, 'result' => $result]);
        } catch (\Exception $e) {
            Log::error("Goyzer sync job failed", [
                'type' => $this->type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}