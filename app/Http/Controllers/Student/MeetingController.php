<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function show(Meeting $meeting)
    {
        $userId = auth()->id();

        $materialCompleted =
            $meeting->hasCompletedMaterial(
                $userId
            );

        $quizCompleted =
            $meeting->hasCompletedQuiz(
                $userId
            );

        $practiceCompleted =
            $meeting->hasCompletedPractice(
                $userId
            );

        $lkpdCompleted =
            $meeting->hasCompletedLkpd(
                $userId
            );

        $evaluationCompleted =
            $meeting->hasCompletedEvaluation(
                $userId
            );

        $quizAvailable =
            $meeting->quizzes()
            ->where('is_active', true)
            ->exists();

        $steps = [

            /*
            |--------------------------------------------------------------------------
            | MATERI
            |--------------------------------------------------------------------------
            */

            [
                'id' => 1,

                'title' => 'Materi',

                'description' =>
                'Pelajari materi...',

                'icon' => 'BookOpen',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->material?->is_active
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->material?->is_active
                ),

                'completed' =>
                $materialCompleted,

                'href' =>
                "/student/meetings/{$meeting->id}/material",
            ],

            /*
            |--------------------------------------------------------------------------
            | KUIS
            |--------------------------------------------------------------------------
            */

            [
                'id' => 2,

                'title' => 'Kuis',

                'description' =>
                'Kerjakan kuis...',

                'icon' =>
                'ClipboardCheck',

                'active' => (
                    $meeting->is_active
                    &&
                    $quizAvailable
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->quiz?->is_active
                    &&
                    $quizCompleted
                ),

                'completed' =>
                $quizCompleted,

                'href' =>
                "/student/meetings/{$meeting->id}/quiz",
            ],

            /*
            |--------------------------------------------------------------------------
            | PRAKTIK
            |--------------------------------------------------------------------------
            */

            [
                'id' => 3,

                'title' =>
                'Praktik Mandiri',

                'description' =>
                'Lakukan praktik...',

                'icon' => 'Code2',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->practice?->is_active
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->practice?->is_active
                    &&
                    $quizCompleted
                ),

                'completed' =>
                $practiceCompleted,

                'href' =>
                "/student/meetings/{$meeting->id}/practice",
            ],

            /*
            |--------------------------------------------------------------------------
            | LKPD
            |--------------------------------------------------------------------------
            */

            [
                'id' => 4,

                'title' => 'LKPD',

                'description' =>
                'Kerjakan LKPD...',

                'icon' =>
                'FileSpreadsheet',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->lkpd?->is_active
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->lkpd?->is_active
                    &&
                    $practiceCompleted
                ),

                'completed' =>
                $lkpdCompleted,

                'href' =>
                "/student/meetings/{$meeting->id}/lkpd",
            ],

            /*
            |--------------------------------------------------------------------------
            | EVALUASI
            |--------------------------------------------------------------------------
            */

            [
                'id' => 5,

                'title' =>
                'Evaluasi',

                'description' =>
                'Kerjakan evaluasi...',

                'icon' =>
                'ClipboardList',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->evaluation?->is_active
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->evaluation?->is_active
                    &&
                    $lkpdCompleted
                ),

                'completed' =>
                $evaluationCompleted,

                'href' =>
                "/student/meetings/{$meeting->id}/evaluation",
            ],
        ];

        return Inertia::render(
            'student/meetings/Show',
            [

                'meeting' => [

                    'id' =>
                    $meeting->id,

                    'title' =>
                    $meeting->title,

                    'subtitle' =>
                    $meeting->subtitle,

                    'description' =>
                    $meeting->description,

                    'opened' =>
                    $meeting->opened_at &&
                        $meeting->closed_at
                        ? now()->between(
                            $meeting->opened_at,
                            $meeting->closed_at
                        )
                        : false,

                    'opened_at' =>
                    $meeting->opened_at
                        ? $meeting->opened_at->format(
                            'd M Y H:i'
                        )
                        : '-',

                    'closed_at' =>
                    $meeting->closed_at
                        ? $meeting->closed_at->format(
                            'd M Y H:i'
                        )
                        : '-',
                ],

                'steps' => $steps,
            ]
        );
    }
}
