<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([\App\Observers\NavbarMenuObserver::class])]
class NavbarMenu extends Model
{
    protected $fillable = ['menu_items', 'cta_label', 'cta_url'];

    protected $casts = [
        'menu_items' => 'array',
    ];
}
