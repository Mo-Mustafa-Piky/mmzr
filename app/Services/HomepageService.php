<?php

namespace App\Services;

use App\Http\Resources\HomepageResource;
use App\Models\Homepage;
use Illuminate\Support\Facades\Cache;

class HomepageService
{
    public function getHomepageData()
    {
        return Cache::rememberForever('homepage_data', function () {
            $homepage = Homepage::first();
            return $homepage ? new HomepageResource($homepage) : null;
        });
    }

    public function clearCache()
    {
        Cache::forget('homepage_data');
    }
}
