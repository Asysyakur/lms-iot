<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\AssessmentResult;
use App\Models\Meeting;
use App\Models\QuizAttempt;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | PRETEST
        |--------------------------------------------------------------------------
        */

        $pretest =
            AssessmentResult::where(
                'student_id',
                $user->id
            )
            ->whereHas(
                'assessment',
                fn($q) =>
                $q->where(
                    'type',
                    'pretest'
                )
            )
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        $quizAverage =
            round(
                QuizAttempt::where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'passed',
                    true
                )
                ->avg('score') ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | MEETING COMPLETED
        |--------------------------------------------------------------------------
        */

        $totalMeetings =
            Meeting::count();

        $completedMeetings =
            Meeting::get()
                ->filter(
                    fn($meeting) =>
                    $meeting->hasCompletedEvaluation(
                        $user->id
                    )
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | FINAL SCORE
        |--------------------------------------------------------------------------
        */

        $finalScore =
            round(
                (
                    ($pretest->score ?? 0)
                    +
                    $quizAverage
                ) / 2
            );

        /*
        |--------------------------------------------------------------------------
        | PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress =
            $totalMeetings > 0
                ? round(
                    (
                        $completedMeetings
                        / $totalMeetings
                    ) * 100
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | SCORES
        |--------------------------------------------------------------------------
        */

        $scores = [

            [
                'label' =>
                'Pre-test',

                'value' =>
                $pretest->score ?? 0,

                'color' =>
                'bg-blue-500',
            ],

            [
                'label' =>
                'Rata-rata Kuis',

                'value' =>
                $quizAverage,

                'color' =>
                'bg-purple-500',
            ],

            [
                'label' =>
                'Nilai Akhir',

                'value' =>
                $finalScore,

                'color' =>
                'bg-orange-500',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $activities = [];

        if ($pretest) {

            $activities[] = [

                'title' =>
                'Menyelesaikan pre-test',

                'status' =>
                'Selesai',
            ];
        }

        if ($quizAverage > 0) {

            $activities[] = [

                'title' =>
                'Mengerjakan kuis',

                'status' =>
                'Nilai ' .
                $quizAverage,
            ];
        }

        return Inertia::render(
            'student/dashboard/Index',
            [

                'student' => [

                    'name' =>
                    $user->name,

                    'class' =>
                    $user->class ?? '-',

                    'nis' =>
                    $user->nis ?? '-',
                ],

                'stats' => [

                    [
                        'title' =>
                        'Pre-test',

                        'value' =>
                        $pretest->score ?? 0,

                        'status' =>
                        'Tuntas',

                        'color' =>
                        'bg-blue-50 text-blue-600',
                    ],

                    [
                        'title' =>
                        'Rata-rata Kuis',

                        'value' =>
                        $quizAverage,

                        'status' =>
                        '≥ 80',

                        'color' =>
                        'bg-purple-50 text-purple-600',
                    ],

                    [
                        'title' =>
                        'Pertemuan Selesai',

                        'value' =>
                        "{$completedMeetings}/{$totalMeetings}",

                        'status' =>
                        'Selesai',

                        'color' =>
                        'bg-emerald-50 text-emerald-600',
                    ],

                    [
                        'title' =>
                        'Nilai Akhir',

                        'value' =>
                        $finalScore,

                        'status' =>
                        'Proses',

                        'color' =>
                        'bg-amber-50 text-amber-600',
                    ],
                ],

                'scores' =>
                $scores,

                'activities' =>
                $activities,

                'progress' =>
                $progress,
            ]
        );
    }
}