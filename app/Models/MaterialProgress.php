<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialProgress extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_id',
        'trigger_answer',
        'reflection_answers',
        'reflection_completed',
        'reading_progress',
        'trigger_score',
        'started_at',
        'last_activity_at',
        'duration_seconds',
    ];

    protected $casts = [
        'reflection_answers' => 'array',
        'reflection_completed' => 'boolean',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];
}