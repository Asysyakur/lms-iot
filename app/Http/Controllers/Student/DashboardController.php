<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\AssessmentResult;
use App\Models\EvaluationSubmission;
use App\Models\LkpdSubmission;
use App\Models\MaterialProgress;
use App\Models\Meeting;
use App\Models\PracticeSubmission;
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
                )->forStudentClass($user->class)
            )
            ->latest()
            ->first();

        $meetings =
            Meeting::with([
                'material',
                'practice',
                'lkpd',
                'evaluation',
            ])->get()->filter(function ($meeting) use ($user) {
                return $meeting->isAccessibleTo($user->class);
            });

        $accessibleMeetingIds =
            $meetings->pluck('id');

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
                    ->whereIn(
                        'meeting_id',
                        $accessibleMeetingIds->all()
                    )
                    ->where(
                        'passed',
                        true
                    )
                    ->avg('score') ?? 0
            );

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
        | ACTIVITY PROGRESS
        |--------------------------------------------------------------------------
        */

        $totalActivities = 0;

        $completedActivities = 0;

        foreach ($meetings as $meeting) {

            /*
|--------------------------------------------------------------------------
| MATERIAL
|--------------------------------------------------------------------------
*/

            if ($meeting->material) {

                $totalActivities++;

                $materialCompleted =
                    MaterialProgress::where(
                        'user_id',
                        $user->id
                    )
                    ->whereIn(
                        'meeting_id',
                        $accessibleMeetingIds->all()
                    )
                    ->where(
                        'meeting_id',
                        $meeting->id
                    )
                    ->where(
                        'reading_progress',
                        '>=',
                        100
                    )
                    ->exists();

                if ($materialCompleted) {
                    $completedActivities++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | PRACTICE
            |--------------------------------------------------------------------------
            */

            if ($meeting->practice) {

                $totalActivities++;

                $practiceCompleted =
                    PracticeSubmission::where(
                        'user_id',
                        $user->id
                    )
                    ->whereIn(
                        'meeting_id',
                        $accessibleMeetingIds->all()
                    )
                    ->where(
                        'meeting_id',
                        $meeting->id
                    )
                    ->exists();

                if ($practiceCompleted) {
                    $completedActivities++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | LKPD
            |--------------------------------------------------------------------------
            */

            if ($meeting->lkpd) {

                $totalActivities++;

                $lkpdCompleted =
                    LkpdSubmission::where(
                        'user_id',
                        $user->id
                    )
                    ->whereIn(
                        'meeting_id',
                        $accessibleMeetingIds->all()
                    )
                    ->where(
                        'meeting_id',
                        $meeting->id
                    )
                    ->exists();

                if ($lkpdCompleted) {
                    $completedActivities++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | EVALUATION
            |--------------------------------------------------------------------------
            */

            if ($meeting->evaluation) {

                $totalActivities++;

                $evaluationCompleted =
                    EvaluationSubmission::where(
                        'user_id',
                        $user->id
                    )
                    ->whereIn(
                        'meeting_id',
                        $accessibleMeetingIds->all()
                    )
                    ->where(
                        'meeting_id',
                        $meeting->id
                    )
                    ->exists();

                if ($evaluationCompleted) {
                    $completedActivities++;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress =
            $totalActivities > 0
            ? round(
                (
                    $completedActivities
                    / $totalActivities
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

        /*
        |--------------------------------------------------------------------------
        | PRETEST
        |--------------------------------------------------------------------------
        */

        if ($pretest) {

            $activities[] = [

                'title' =>
                'Menyelesaikan pre-test',

                'status' =>
                'Selesai',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        if ($quizAverage > 0) {

            $activities[] = [

                'title' =>
                'Mengerjakan kuis',

                'status' =>
                'Nilai ' . $quizAverage,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MATERIAL
        |--------------------------------------------------------------------------
        */

        $materialCount =
            MaterialProgress::where(
                'user_id',
                $user->id
            )
            ->whereIn(
                'meeting_id',
                $accessibleMeetingIds->all()
            )
            ->where(
                'reading_progress',
                '>=',
                100
            )
            ->count();

        if ($materialCount > 0) {

            $activities[] = [

                'title' =>
                'Menyelesaikan materi pembelajaran',

                'status' =>
                $materialCount . ' materi',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PRACTICE
        |--------------------------------------------------------------------------
        */

        $practiceCount =
            PracticeSubmission::where(
                'user_id',
                $user->id
            )
            ->whereIn(
                'meeting_id',
                $accessibleMeetingIds->all()
            )
            ->count();

        if ($practiceCount > 0) {

            $activities[] = [

                'title' =>
                'Mengumpulkan praktikum',

                'status' =>
                $practiceCount . ' praktik',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | LKPD
        |--------------------------------------------------------------------------
        */

        $lkpdCount =
            LkpdSubmission::where(
                'user_id',
                $user->id
            )
            ->whereIn(
                'meeting_id',
                $accessibleMeetingIds->all()
            )
            ->count();

        if ($lkpdCount > 0) {

            $activities[] = [

                'title' =>
                'Mengumpulkan LKPD',

                'status' =>
                $lkpdCount . ' LKPD',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | EVALUATION
        |--------------------------------------------------------------------------
        */

        $evaluationCount =
            EvaluationSubmission::where(
                'user_id',
                $user->id
            )
            ->whereIn(
                'meeting_id',
                $accessibleMeetingIds->all()
            )
            ->count();

        if ($evaluationCount > 0) {

            $activities[] = [

                'title' =>
                'Menyelesaikan evaluasi',

                'status' =>
                $evaluationCount . ' evaluasi',
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
                        'Aktivitas Selesai',

                        'value' =>
                        "{$completedActivities}/{$totalActivities}",

                        'status' =>
                        'Progress',

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
