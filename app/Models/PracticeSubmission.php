<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_id',
        'project_url',
        'score',
        'submitted_at',
    ];
}
