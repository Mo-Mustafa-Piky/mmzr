<?php

namespace App\Observers;

use App\Models\FaqSettings;
use App\Services\FooterFaqService;

class FaqSettingsObserver
{
    public function __construct(protected FooterFaqService $footerFaqService)
    {
    }

    public function saved(FaqSettings $settings): void
    {
        $this->footerFaqService->clearCache();
    }

    public function deleted(FaqSettings $settings): void
    {
        $this->footerFaqService->clearCache();
    }
}
