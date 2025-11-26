<?php

namespace App\Services;

use App\Models\Award;
use App\Models\AwardsBanner;
use Illuminate\Support\Facades\Cache;

class AwardsService
{
    public function getBanner()
    {
        return Cache::rememberForever('awards_banner', function () {
            return AwardsBanner::where('is_active', true)->first();
        });
    }

    public function getAwards($perPage = 12)
    {
        $page = request('page', 1);
        $cacheKey = "awards_page_{$page}_{$perPage}";
        
        return Cache::rememberForever($cacheKey, function () use ($perPage) {
            return Award::where('is_active', true)
                ->orderBy('order')
                ->paginate($perPage);
        });
    }

    public function clearCache(): void
    {
        Cache::forget('awards_banner');
        Cache::flush();
    }
}
