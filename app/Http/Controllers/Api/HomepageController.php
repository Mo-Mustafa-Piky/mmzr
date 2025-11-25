<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HomepageService;
use App\Traits\V1\ResponseTrait;

class HomepageController extends Controller
{
    use ResponseTrait;

    public function __construct(private HomepageService $homepageService) {}

    public function index()
    {
        $homepage = $this->homepageService->getHomepageData();
        return $this->successResponse($homepage);
    }
}
