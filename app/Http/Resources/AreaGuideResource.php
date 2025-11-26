<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaGuideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'area_name' => $this->area_name,
            'description' => $this->description,
            'badge_text' => $this->badge_text,
            'button_text' => $this->button_text,
            'slug' => $this->slug,
            'featured_image' => $this->getFirstMediaUrl('featured_image'),
            'full_content' => $this->full_content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
        ];
    }
}
