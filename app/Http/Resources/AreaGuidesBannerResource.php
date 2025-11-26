<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaGuidesBannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'background_image' => $this->getFirstMediaUrl('background_image'),
            'download_button_text' => $this->download_button_text,
            'download_link' => $this->download_link,
            'video_button_text' => $this->video_button_text,
            'video_link' => $this->video_link,
        ];
    }
}
