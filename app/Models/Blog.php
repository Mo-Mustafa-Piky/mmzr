<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected static function booted()
    {
        static::saved(function ($blog) {
            \Cache::forget('blogs_list');
            \Cache::forget("blog_{$blog->slug}");
        });

        static::deleted(function ($blog) {
            \Cache::forget('blogs_list');
            \Cache::forget("blog_{$blog->slug}");
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'content',
        'author_name',
        'status',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();

        $this->addMediaCollection('author_image')
            ->singleFile();
    }
}
