<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id',
        'quiz_id',
        'selected_answer',
        'is_correct'
    ];

    public function answers()
    {
        return $this->hasMany(
            QuizAnswer::class
        );
    }

    public function quiz()
    {
        return $this->belongsTo(
            Quiz::class
        );
    }
}
