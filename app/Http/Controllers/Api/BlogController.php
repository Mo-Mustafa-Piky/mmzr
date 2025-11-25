<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use App\Traits\V1\ResponseTrait;

class BlogController extends Controller
{
    use ResponseTrait;

    public function __construct(private BlogService $blogService) {}

    public function index()
    {
        $blogs = $this->blogService->getAllPublished();
        return $this->successResponse($blogs);
    }

    public function show($slug)
    {
        $blog = $this->blogService->getBySlug($slug);
        return $this->successResponse($this->blogService->formatBlog($blog, true));
    }
}
