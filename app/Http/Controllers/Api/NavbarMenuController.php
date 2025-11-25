<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NavbarMenuResource;
use App\Services\NavbarMenuService;

class NavbarMenuController extends Controller
{
    public function __construct(protected NavbarMenuService $navbarMenuService)
    {
    }

    public function index()
    {
        $menu = $this->navbarMenuService->getNavbarMenu();
        return new NavbarMenuResource($menu);
    }
}
