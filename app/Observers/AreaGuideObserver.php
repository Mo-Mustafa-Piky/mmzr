<?php

namespace App\Observers;

use App\Models\AreaGuide;
use App\Services\AreaGuidesService;

class AreaGuideObserver
{
    public function __construct(protected AreaGuidesService $areaGuidesService)
    {
    }

    public function saved(AreaGuide $guide): void
    {
        $this->areaGuidesService->clearCache();
    }

    public function deleted(AreaGuide $guide): void
    {
        $this->areaGuidesService->clearCache();
    }
}
