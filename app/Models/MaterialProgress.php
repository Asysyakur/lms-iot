<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialProgress extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_id',
        'reading_progress',
        'trigger_answer',
        'reflection_answers',
        'reflection_completed',
    ];

    protected $casts = [
        'reflection_answers' => 'array',
        'reflection_completed' => 'boolean',
    ];
}
