<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'class',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class, 'student_id');
    }

    public function materialProgress()
    {
        return $this->hasMany(
            \App\Models\MaterialProgress::class,
            'user_id'
        );
    }

    public function practiceSubmissions()
    {
        return $this->hasMany(
            \App\Models\PracticeSubmission::class,
            'user_id'
        );
    }

    public function lkpdSubmissions()
    {
        return $this->hasMany(
            \App\Models\LkpdSubmission::class,
            'user_id'
        );
    }

    public function evaluationSubmissions()
    {
        return $this->hasMany(
            \App\Models\EvaluationSubmission::class,
            'user_id'
        );
    }

    public function quizAttempts()
    {
        return $this->hasMany(
            \App\Models\QuizAttempt::class,
            'user_id'
        );
    }
}
