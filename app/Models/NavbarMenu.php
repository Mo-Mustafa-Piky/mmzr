<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavbarMenu extends Model
{
    protected $fillable = ['menu_items', 'cta_label', 'cta_url'];

    protected $casts = [
        'menu_items' => 'array',
    ];
}
