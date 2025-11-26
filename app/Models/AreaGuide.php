<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([\App\Observers\AreaGuideObserver::class])]
class AreaGuide extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'area_name',
        'description',
        'badge_text',
        'button_text',
        'slug',
        'full_content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
    }
}
