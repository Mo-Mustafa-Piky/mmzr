<?php

namespace App\Services;

use App\Models\BlogsBanner;

class BlogsBannerService
{
    public function getActiveBanner()
    {
        $banner = BlogsBanner::where('is_active', true)->first();

        if (!$banner) {
            return null;
        }

        return [
            'id' => $banner->id,
            'title' => $banner->title,
            'button_text' => $banner->button_text,
            'button_link' => $banner->button_link,
            'background' => $banner->getFirstMediaUrl('background'),
        ];
    }
}
