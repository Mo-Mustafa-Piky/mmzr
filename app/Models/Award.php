<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([\App\Observers\AwardObserver::class])]
class Award extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'award_title',
        'award_organization',
        'year',
        'badge_text',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('award_image')->singleFile();
    }
}
