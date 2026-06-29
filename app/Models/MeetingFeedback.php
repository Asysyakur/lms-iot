<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingFeedback extends Model
{
    protected $fillable = [
        'meeting_id',
        'user_id',
        'feedback',
    ];
}
