<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AwardResource;
use App\Http\Resources\AwardsBannerResource;
use App\Services\AwardsService;
use Illuminate\Http\Request;

class AwardsController extends Controller
{
    public function __construct(protected AwardsService $awardsService)
    {
    }

    public function banner()
    {
        $banner = $this->awardsService->getBanner();
        return new AwardsBannerResource($banner);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $awards = $this->awardsService->getAwards($perPage);
        return AwardResource::collection($awards);
    }
}
