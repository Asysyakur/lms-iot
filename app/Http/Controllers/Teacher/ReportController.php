<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

use App\Models\Meeting;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        $meetings = Meeting::orderBy(
            'meeting_number'
        )->get();

        $menus = [

            [
                'id' => 'prepost',

                'title' =>
                'Hasil Pre-test & Post-test',

                'description' =>
                'Lihat nilai pre-test dan post-test siswa.',

                'icon' => '📄',

                'href' =>
                '/teacher/reports/assessments',

                'color' =>
                'from-blue-500 to-cyan-500',
            ]
        ];

        foreach ($meetings as $meeting) {

            $menus[] = [

                'id' =>
                'meeting-' . $meeting->id,

                'title' =>
                'Hasil ' . $meeting->title,

                'description' =>
                'Monitoring aktivitas siswa.',

                'icon' =>
                (string) $meeting->meeting_number,

                'href' =>
                '/teacher/reports/meetings/' . $meeting->id,

                'color' =>
                'from-emerald-500 to-teal-500',
            ];
        }

        return Inertia::render(
            'teacher/reports/Index',
            [
                'menus' => $menus,
            ]
        );
    }
    /*
    |--------------------------------------------------------------------------
    | REPORT PERTEMUAN
    |--------------------------------------------------------------------------
    */

    public function meetings()
    {
        $studentsCount =
            User::where(
                'role',
                'student'
            )->count();

        $meetings = Meeting::with([
            'material',
            'quizzes',
            'practice',
            'lkpd',
            'evaluation',
            'practiceSubmissions',
            'lkpdSubmissions',
            'evaluationSubmissions',
        ])
            ->orderBy(
                'meeting_number'
            )
            ->get()
            ->map(function ($meeting) use ($studentsCount) {

                $practiceCount =
                    $meeting->practiceSubmissions
                    ->count();

                $lkpdCount =
                    $meeting->lkpdSubmissions
                    ->count();

                $evaluationCount =
                    $meeting->evaluationSubmissions
                    ->count();

                $completion =
                    $studentsCount > 0
                    ? round(
                        (
                            $evaluationCount /
                            $studentsCount
                        ) * 100
                    )
                    : 0;

                return [

                    'id' =>
                    $meeting->id,

                    'meeting_number' =>
                    $meeting->meeting_number,

                    'title' =>
                    $meeting->title,

                    'material_active' =>
                    $meeting->material?->is_active,

                    'quiz_count' =>
                    $meeting->quizzes->count(),

                    'practice_submissions' =>
                    $practiceCount,

                    'lkpd_submissions' =>
                    $lkpdCount,

                    'evaluation_submissions' =>
                    $evaluationCount,

                    'completion' =>
                    $completion,

                    'status' =>
                    $meeting->is_active
                        ? 'Aktif'
                        : 'Nonaktif',
                ];
            });

        return Inertia::render(
            'teacher/reports/MeetingSummary',
            [

                'meetings' =>
                $meetings,

                'studentsCount' =>
                $studentsCount,
            ]
        );
    }

    public function meetingDetail(
        Meeting $meeting
    ) {

        $students = User::where(
            'role',
            'student'
        )
            ->with([
                'materialProgress',
                'practiceSubmissions',
                'lkpdSubmissions',
                'evaluationSubmissions',
                'quizAttempts',
            ])
            ->get()
            ->map(function ($student) use ($meeting) {
                $materialProgress =
                    $student->materialProgress
                    ->where('meeting_id', $meeting->id)
                    ->first();

                $quizAttempt =
                    $student->quizAttempts
                    ->where('meeting_id', $meeting->id)
                    ->first();

                $practice =
                    $student->practiceSubmissions
                    ->where('meeting_id', $meeting->id)
                    ->first();

                $lkpd =
                    $student->lkpdSubmissions
                    ->where('meeting_id', $meeting->id)
                    ->first();

                $evaluation =
                    $student->evaluationSubmissions
                    ->where('meeting_id', $meeting->id)
                    ->first();

                return [

                    'id' =>
                    $student->id,

                    'name' =>
                    $student->name,

                    'triggerAnswer' =>
                    $materialProgress?->trigger_answer ?? "Belum",

                    'triggerScore' =>
                    $materialProgress?->trigger_score ?? 0,

                    'accessTime' =>
                    $materialProgress
                        ? gmdate(
                            'H:i:s',
                            $materialProgress->duration_seconds
                        )
                        : '00:00:00',

                    'reflectionAnswer' =>
                    $materialProgress?->reflection_answers ?? "[Belum]",

                    'quiz' =>
                    $quizAttempt?->score ?? 0,

                    'practiceScore' =>
                    $practice?->score ?? 0,

                    'practice' =>
                    $practice?->project_url ?? null,

                    'lkpd' =>
                    $lkpd?->file_path ?? null,

                    'evaluation' =>
                    $evaluation?->answers ?? null,

                    'evaluationScore' =>
                    $evaluation?->score ?? 0,
                ];
            });

        return Inertia::render(
            'teacher/reports/MeetingDetail',
            [

                'meeting' =>
                $meeting,

                'students' =>
                $students,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT PRETEST / POSTTEST
    |--------------------------------------------------------------------------
    */

    public function assessments()
    {
        $students = User::where(
            'role',
            'student'
        )
            ->with([
                'assessmentResults.assessment',
            ])
            ->get()
            ->map(function ($student) {

                $pretest =
                    $student->assessmentResults
                    ->first(function ($result) {

                        return
                            $result->assessment?->type
                            === 'pretest';
                    });

                $posttest =
                    $student->assessmentResults
                    ->first(function ($result) {

                        return
                            $result->assessment?->type
                            === 'posttest';
                    });

                return [

                    'id' =>
                    $student->id,

                    'name' =>
                    $student->name,

                    'class' =>
                    $student->class,

                    'pretest_score' =>
                    $pretest?->score,

                    'posttest_score' =>
                    $posttest?->score,

                    'pretest_status' =>
                    $pretest
                        ? 'Selesai'
                        : 'Belum',

                    'posttest_status' =>
                    $posttest
                        ? 'Selesai'
                        : 'Belum',
                ];
            });

        return Inertia::render(
            'teacher/reports/PrePostReport',
            [

                'students' =>
                $students,
            ]
        );
    }

    public function saveScores(
        Request $request,
        Meeting $meeting
    ) {

        foreach (
            $request->students as $studentData
        ) {

            /*
        |--------------------------------------------------------------------------
        | MATERIAL PROGRESS
        |--------------------------------------------------------------------------
        */

            $materialProgress =
                \App\Models\MaterialProgress::where(
                    'user_id',
                    $studentData['id']
                )
                ->where(
                    'meeting_id',
                    $meeting->id
                )
                ->first();

            if ($materialProgress) {

                $materialProgress->update([

                    'trigger_score' =>
                    $studentData['triggerScore'] ?? 0,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | PRACTICE SUBMISSION
        |--------------------------------------------------------------------------
        */

            $practice =
                \App\Models\PracticeSubmission::where(
                    'user_id',
                    $studentData['id']
                )
                ->where(
                    'meeting_id',
                    $meeting->id
                )
                ->first();

            if ($practice) {

                $practice->update([

                    'score' =>
                    $studentData['practiceScore'] ?? 0,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | EVALUATION SUBMISSION
        |--------------------------------------------------------------------------
        */

            $evaluation =
                \App\Models\EvaluationSubmission::where(
                    'user_id',
                    $studentData['id']
                )
                ->where(
                    'meeting_id',
                    $meeting->id
                )
                ->first();

            if ($evaluation) {

                $evaluation->update([

                    'score' =>
                    $studentData['evaluationScore'] ?? 0,
                ]);
            }
        }

        return back()->with(
            'success',
            'Nilai berhasil disimpan.'
        );
    }
}
