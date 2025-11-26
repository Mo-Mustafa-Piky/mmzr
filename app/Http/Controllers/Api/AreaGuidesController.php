<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaGuideResource;
use App\Http\Resources\AreaGuidesBannerResource;
use App\Services\AreaGuidesService;
use Illuminate\Http\Request;

class AreaGuidesController extends Controller
{
    public function __construct(protected AreaGuidesService $areaGuidesService)
    {
    }

    public function banner()
    {
        $banner = $this->areaGuidesService->getBanner();
        return new AreaGuidesBannerResource($banner);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $guides = $this->areaGuidesService->getAreaGuides($perPage);
        return AreaGuideResource::collection($guides);
    }
}
