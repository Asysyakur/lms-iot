<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'assessment_id',
        'question',
        'type',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'answer',
        'is_active',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
