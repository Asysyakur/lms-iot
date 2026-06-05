<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'meeting_id',
        'questions',
        'is_active',
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
