<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqSettingsResource;
use App\Http\Resources\FooterSettingsResource;
use App\Services\FooterFaqService;

class FooterFaqController extends Controller
{
    public function __construct(protected FooterFaqService $footerFaqService)
    {
    }

    public function footer()
    {
        $footer = $this->footerFaqService->getFooter();
        return new FooterSettingsResource($footer);
    }

    public function faqs()
    {
        $faqs = $this->footerFaqService->getFaqs();
        return new FaqSettingsResource($faqs);
    }
}
