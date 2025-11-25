<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogsBanner extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected static function booted()
    {
        static::saved(function () {
            \Cache::forget('blogs_banner');
        });

        static::deleted(function () {
            \Cache::forget('blogs_banner');
        });
    }

    protected $table = 'blogs_banner';

    protected $fillable = [
        'title',
        'button_text',
        'button_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background')
            ->singleFile();
    }
}
