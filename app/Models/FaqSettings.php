<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([\App\Observers\FaqSettingsObserver::class])]
class FaqSettings extends Model
{
    protected $fillable = ['faqs'];

    protected $casts = [
        'faqs' => 'array',
    ];
}
