<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'award_title' => $this->award_title,
            'award_organization' => $this->award_organization,
            'year' => $this->year,
            'badge_text' => $this->badge_text,
            'description' => $this->description,
            'award_image' => $this->getFirstMediaUrl('award_image'),
            'is_featured' => $this->is_featured,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
        ];
    }
}
