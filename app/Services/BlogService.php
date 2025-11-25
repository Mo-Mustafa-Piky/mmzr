<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

class BlogService
{
    public function getAllPublished()
    {
        return Cache::rememberForever('blogs_list', function () {
            return Blog::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->get()
                ->map(fn($blog) => $this->formatBlog($blog));
        });
    }

    public function getBySlug(string $slug)
    {
        return Cache::rememberForever("blog_{$slug}", function () use ($slug) {
            return Blog::where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();
        });
    }

    public function formatBlog($blog, bool $full = false)
    {
        $data = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'author_name' => $blog->author_name,
            'is_featured' => $blog->is_featured,
            'published_at' => $blog->published_at,
            'featured_image' => $blog->getFirstMediaUrl('featured_image'),
            'author_image' => $blog->getFirstMediaUrl('author_image'),
        ];

        if ($full) {
            $data['meta_title'] = $blog->meta_title;
            $data['meta_description'] = $blog->meta_description;
            $data['meta_keywords'] = $blog->meta_keywords;
        }

        return $data;
    }

    public function clearCache($slug = null)
    {
        Cache::forget('blogs_list');
        if ($slug) {
            Cache::forget("blog_{$slug}");
        }
    }
}
