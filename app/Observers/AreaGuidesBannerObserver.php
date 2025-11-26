<?php

namespace App\Observers;

use App\Models\AreaGuidesBanner;
use App\Services\AreaGuidesService;

class AreaGuidesBannerObserver
{
    public function __construct(protected AreaGuidesService $areaGuidesService)
    {
    }

    public function saved(AreaGuidesBanner $banner): void
    {
        $this->areaGuidesService->clearCache();
    }

    public function deleted(AreaGuidesBanner $banner): void
    {
        $this->areaGuidesService->clearCache();
    }
}
