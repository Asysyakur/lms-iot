<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkpdSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'submitted_at',
    ];
}