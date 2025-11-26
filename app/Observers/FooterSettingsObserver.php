<?php

namespace App\Observers;

use App\Models\FooterSettings;
use App\Services\FooterFaqService;

class FooterSettingsObserver
{
    public function __construct(protected FooterFaqService $footerFaqService)
    {
    }

    public function saved(FooterSettings $settings): void
    {
        $this->footerFaqService->clearCache();
    }

    public function deleted(FooterSettings $settings): void
    {
        $this->footerFaqService->clearCache();
    }
}
