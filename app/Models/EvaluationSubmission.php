<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_id',
        'answers',
        'submitted_at',
    ];

    protected $casts = [
        'answers' =>
        'array',
        'submitted_at' =>
        'datetime',
    ];
}