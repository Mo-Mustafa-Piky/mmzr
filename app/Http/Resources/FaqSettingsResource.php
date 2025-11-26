<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'faqs' => collect($this->faqs)->where('is_active', true)->sortBy('order')->values()->all(),
        ];
    }
}
