<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\Practice;
use App\Models\Lkpd;
use App\Models\Evaluation;

use App\Models\MaterialProgress;
use App\Models\PracticeSubmission;
use App\Models\LkpdSubmission;
use App\Models\EvaluationSubmission;

class Meeting extends Model
{
    protected $fillable = [
        'meeting_number',
        'title',
        'description',
        'status',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | MAIN CONTENT
    |--------------------------------------------------------------------------
    */

    public function material()
    {
        return $this->hasOne(
            Material::class
        );
    }

    public function quizzes()
    {
        return $this->hasMany(
            Quiz::class
        );
    }

    public function practice()
    {
        return $this->hasOne(
            Practice::class
        );
    }

    public function lkpd()
    {
        return $this->hasOne(
            Lkpd::class
        );
    }

    public function evaluation()
    {
        return $this->hasOne(
            Evaluation::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER PROGRESS
    |--------------------------------------------------------------------------
    */

    public function materialProgress()
    {
        return $this->hasMany(
            MaterialProgress::class
        );
    }

    public function practiceSubmissions()
    {
        return $this->hasMany(
            PracticeSubmission::class
        );
    }

    public function lkpdSubmissions()
    {
        return $this->hasMany(
            LkpdSubmission::class
        );
    }

    public function evaluationSubmissions()
    {
        return $this->hasMany(
            EvaluationSubmission::class
        );
    }

    public function quizAttempts()
    {
        return $this->hasMany(
            QuizAttempt::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    public function hasCompletedMaterial(
        $userId
    ): bool {

        return $this->materialProgress()
            ->where('user_id', $userId)
            ->where(
                'reflection_completed',
                true
            )
            ->exists();
    }

    public function hasCompletedPractice(
        $userId
    ): bool {

        return $this->practiceSubmissions()
            ->where('user_id', $userId)
            ->exists();
    }

    public function hasCompletedLkpd(
        $userId
    ): bool {

        return $this->lkpdSubmissions()
            ->where('user_id', $userId)
            ->exists();
    }

    public function hasCompletedEvaluation(
        $userId
    ): bool {

        return $this->evaluationSubmissions()
            ->where('user_id', $userId)
            ->exists();
    }

    public function hasCompletedQuiz(
        $userId
    ): bool {

        return $this->quizAttempts()
            ->where('user_id', $userId)
            ->where('passed', true)
            ->exists();
    }
}
