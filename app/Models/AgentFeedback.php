<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentFeedback extends Model
{
    protected $table = 'agent_feedback';

    protected $fillable = [
        'contact_id',
        'lead_id',
        'feedback_id',
        'remarks',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
