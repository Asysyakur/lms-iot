<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'meeting_id',
        'question',
        'code',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'answer',
        'is_active',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
