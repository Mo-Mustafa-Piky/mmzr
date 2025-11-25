<?php

namespace App\Observers;

use App\Models\NavbarMenu;
use App\Services\NavbarMenuService;

class NavbarMenuObserver
{
    public function __construct(protected NavbarMenuService $navbarMenuService)
    {
    }

    public function saved(NavbarMenu $navbarMenu): void
    {
        $this->navbarMenuService->clearCache();
    }

    public function deleted(NavbarMenu $navbarMenu): void
    {
        $this->navbarMenuService->clearCache();
    }
}
