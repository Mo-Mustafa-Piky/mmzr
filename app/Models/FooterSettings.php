<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([\App\Observers\FooterSettingsObserver::class])]
class FooterSettings extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'column_1_title', 'column_1_items', 'column_1_is_active',
        'column_2_title', 'column_2_items', 'column_2_is_active',
        'column_3_title', 'column_3_items', 'column_3_is_active',
        'column_4_title', 'column_4_items', 'column_4_is_active',
        'column_5_title', 'column_5_items', 'column_5_is_active',
        'copyright_text', 'powered_by_text', 'powered_by_link',
        'privacy_link', 'terms_link', 'sitemap_link', 'is_active',
    ];

    protected $casts = [
        'column_1_items' => 'array',
        'column_2_items' => 'array',
        'column_3_items' => 'array',
        'column_4_items' => 'array',
        'column_5_items' => 'array',
        'column_1_is_active' => 'boolean',
        'column_2_is_active' => 'boolean',
        'column_3_is_active' => 'boolean',
        'column_4_is_active' => 'boolean',
        'column_5_is_active' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('footer_logo')->singleFile();
    }
}
