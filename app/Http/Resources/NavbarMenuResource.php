<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavbarMenuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'menu_items' => $this->menu_items,
            'cta_label' => $this->cta_label,
            'cta_url' => $this->cta_url,
        ];
    }
}
