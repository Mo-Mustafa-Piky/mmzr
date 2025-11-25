<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlogsBannerService;
use App\Traits\V1\ResponseTrait;

class BlogsBannerController extends Controller
{
    use ResponseTrait;

    public function __construct(private BlogsBannerService $bannerService) {}

    public function index()
    {
        $banner = $this->bannerService->getActiveBanner();
        return $this->successResponse($banner);
    }
}
