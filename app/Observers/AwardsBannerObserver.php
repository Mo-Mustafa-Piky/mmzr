<?php

namespace App\Observers;

use App\Models\AwardsBanner;
use App\Services\AwardsService;

class AwardsBannerObserver
{
    public function __construct(protected AwardsService $awardsService)
    {
    }

    public function saved(AwardsBanner $banner): void
    {
        $this->awardsService->clearCache();
    }

    public function deleted(AwardsBanner $banner): void
    {
        $this->awardsService->clearCache();
    }
}
