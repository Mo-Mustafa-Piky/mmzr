<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooterSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'footer_logo' => $this->getFirstMediaUrl('footer_logo'),
            'column_1' => [
                'title' => $this->column_1_title,
                'items' => $this->column_1_items,
                'is_active' => $this->column_1_is_active,
            ],
            'column_2' => [
                'title' => $this->column_2_title,
                'items' => $this->column_2_items,
                'is_active' => $this->column_2_is_active,
            ],
            'column_3' => [
                'title' => $this->column_3_title,
                'items' => $this->column_3_items,
                'is_active' => $this->column_3_is_active,
            ],
            'column_4' => [
                'title' => $this->column_4_title,
                'items' => $this->column_4_items,
                'is_active' => $this->column_4_is_active,
            ],
            'column_5' => [
                'title' => $this->column_5_title,
                'items' => $this->column_5_items,
                'is_active' => $this->column_5_is_active,
            ],
            'copyright_text' => $this->copyright_text,
            'powered_by_text' => $this->powered_by_text,
            'powered_by_link' => $this->powered_by_link,
            'privacy_link' => $this->privacy_link,
            'terms_link' => $this->terms_link,
            'sitemap_link' => $this->sitemap_link,
        ];
    }
}
