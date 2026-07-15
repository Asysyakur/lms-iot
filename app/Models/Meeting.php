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
        'target_class',
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

    public function isAccessibleTo(?string $class): bool
    {
        return $this->target_class === null
            || $this->target_class === $class;
    }

    public function students()
    {
        $students = User::where(
            'role',
            'student'
        );

        if ($this->target_class) {
            $students->where(
                'class',
                $this->target_class
            );
        }

        return $students->get()->map(function ($student) {

            /**
             * MATERIAL
             */
            $material =
                $this->materialProgress
                ->where(
                    'user_id',
                    $student->id
                )
                ->first();

            /**
             * QUIZ
             */
            $quiz =
                $this->quizAttempts
                ->where(
                    'user_id',
                    $student->id
                )
                ->sortByDesc('score')
                ->first();

            /**
             * PRACTICE
             */
            $practice =
                $this->practiceSubmissions
                ->where(
                    'user_id',
                    $student->id
                )
                ->first();

            /**
             * LKPD
             */
            $lkpd =
                $this->lkpdSubmissions
                ->where(
                    'user_id',
                    $student->id
                )
                ->first();

            /**
             * EVALUATION
             */
            $evaluation =
                $this->evaluationSubmissions
                ->where(
                    'user_id',
                    $student->id
                )
                ->first();

            return (object) [

                'id' =>
                $student->id,

                'name' =>
                $student->name,

                'triggerAnswer' =>
                $material?->trigger_answer,

                'triggerScore' =>
                $material?->trigger_score ?? 0,

                'reflectionAnswer' =>
                $material?->reflection_answers,

                'duration_seconds' =>
                $material?->duration_seconds ?? 0,

                'quiz' =>
                $quiz?->score ?? 0,

                'practice' =>
                $practice?->project_url,

                'practiceText' =>
                $practice?->submission_text,

                'practiceScore' =>
                $practice?->score ?? 0,

                'lkpd' =>
                $lkpd?->file_path,

                'evaluation' =>
                $evaluation?->answers,

                'evaluationScore' =>
                $evaluation?->score ?? 0,
            ];
        });
    }
}
