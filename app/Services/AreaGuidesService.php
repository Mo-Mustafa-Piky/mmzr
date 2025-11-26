<?php

namespace App\Services;

use App\Models\AreaGuide;
use App\Models\AreaGuidesBanner;
use Illuminate\Support\Facades\Cache;

class AreaGuidesService
{
    public function getBanner()
    {
        return Cache::rememberForever('area_guides_banner', function () {
            return AreaGuidesBanner::where('is_active', true)->first();
        });
    }

    public function getAreaGuides($perPage = 12)
    {
        $page = request('page', 1);
        $cacheKey = "area_guides_page_{$page}_{$perPage}";
        
        return Cache::rememberForever($cacheKey, function () use ($perPage) {
            return AreaGuide::where('is_active', true)
                ->orderBy('order')
                ->paginate($perPage);
        });
    }

    public function clearCache(): void
    {
        Cache::forget('area_guides_banner');
        Cache::flush(); // Clear all area_guides_page_* keys
    }
}
