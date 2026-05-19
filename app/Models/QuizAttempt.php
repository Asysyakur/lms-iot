<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_id',
        'total_questions',
        'attempt_number',
        'score',
        'correct_answers',
        'passed',
        'submitted_at',
    ];

    public function answers()
    {
        return $this->hasMany(
            QuizAnswer::class
        );
    }
}
