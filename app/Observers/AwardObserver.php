<?php

namespace App\Observers;

use App\Models\Award;
use App\Services\AwardsService;

class AwardObserver
{
    public function __construct(protected AwardsService $awardsService)
    {
    }

    public function saved(Award $award): void
    {
        $this->awardsService->clearCache();
    }

    public function deleted(Award $award): void
    {
        $this->awardsService->clearCache();
    }
}
