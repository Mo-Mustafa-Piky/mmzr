<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\SettingObserver;

#[ObservedBy([SettingObserver::class])]
class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_description',
        'site_email',
        'site_phone',
        'site_address',
        'logo',
        'favicon',
        'footer_logo',
        'footer_text',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'whatsapp',
        'telegram',
        'meta_keywords',
        'meta_description',
        'google_analytics',
        'facebook_pixel',
        'maintenance_mode',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];
}
