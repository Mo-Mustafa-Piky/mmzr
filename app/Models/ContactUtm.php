<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactUtm extends Model
{
    protected $fillable = [
        'contact_id',
        'utm_id',
        'utm_term',
        'utm_content',
        'utm_ad_set',
        'utm_campaign_name',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
