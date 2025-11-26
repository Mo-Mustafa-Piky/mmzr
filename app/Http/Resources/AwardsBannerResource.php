<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardsBannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'background_image' => $this->getFirstMediaUrl('background_image'),
            'button_one_text' => $this->button_one_text,
            'button_one_link' => $this->button_one_link,
            'button_two_text' => $this->button_two_text,
            'button_two_link' => $this->button_two_link,
        ];
    }
}
