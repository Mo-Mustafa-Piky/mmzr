<?php

namespace App\Services;

use App\Models\FaqSettings;
use App\Models\FooterSettings;
use Illuminate\Support\Facades\Cache;

class FooterFaqService
{
    public function getFooter()
    {
        return Cache::rememberForever('footer_settings', function () {
            return FooterSettings::where('is_active', true)->first();
        });
    }

    public function getFaqs()
    {
        return Cache::rememberForever('faq_settings', function () {
            return FaqSettings::first();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('footer_settings');
        Cache::forget('faq_settings');
    }
}
