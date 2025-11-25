<?php

namespace App\Services;

use App\Models\NavbarMenu;
use Illuminate\Support\Facades\Cache;

class NavbarMenuService
{
    public function getNavbarMenu()
    {
        return Cache::rememberForever('navbar_menu', function () {
            return NavbarMenu::first();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('navbar_menu');
    }
}
