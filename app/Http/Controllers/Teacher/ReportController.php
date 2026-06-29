<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\FullReportExport;
use App\Exports\MeetingDetailExport;
use App\Exports\PrePostReportExport;
use App\Http\Controllers\Controller;
use App\Models\EvaluationSubmission;
use App\Models\MaterialProgress;
use App\Models\Meeting;
use App\Models\MeetingFeedback;
use App\Models\PracticeSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with([
            'materialProgress',
            'practiceSubmissions',
            'lkpdSubmissions',
            'evaluationSubmissions',
        ])
            ->orderBy('meeting_number')
            ->get();

        $totalStudents = User::where(
            'role',
            'student'
        )->count();

        $meetingProgress = $meetings->map(
            function ($meeting) use ($totalStudents) {

                $completedStudents =
                    $meeting->evaluationSubmissions
                        ->pluck('user_id')
                        ->unique()
                        ->count();

                $percentage =
                    $totalStudents > 0
                    ? round(
                        ($completedStudents / $totalStudents) * 100
                    )
                    : 0;

                return [

                    'id' => $meeting->id,

                    'title' => $meeting->title,

                    'percentage' => $percentage,
                ];
            }
        );

        $menus = [

            [
                'id' => 'prepost',

                'title' => 'Hasil Pre-test & Post-test',

                'description' => 'Lihat nilai pre-test dan post-test siswa.',

                'icon' => '📄',

                'href' => '/teacher/reports/assessments',

                'color' => 'from-blue-500 to-cyan-500',
            ],
        ];

        foreach ($meetings as $meeting) {

            $menus[] = [

                'id' => 'meeting-'.$meeting->id,

                'title' => 'Hasil '.$meeting->title,

                'description' => 'Monitoring aktivitas siswa.',

                'icon' => (string) $meeting->meeting_number,

                'href' => '/teacher/reports/meetings/'.$meeting->id,

                'color' => 'from-emerald-500 to-teal-500',
            ];
        }

        return Inertia::render(
            'teacher/reports/Index',
            [

                'menus' => $menus,

                'meetingProgress' => $meetingProgress,
            ]
        );
    }

    public function meetingDetail(
        Meeting $meeting
    ) {

        $meeting->load('practice');

        $feedbacks = MeetingFeedback::where(
            'meeting_id',
            $meeting->id
        )
            ->get()
            ->keyBy('user_id');

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
            ->map(function ($student) use ($meeting, $feedbacks) {
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

                    'id' => $student->id,

                    'name' => $student->name,

                    'triggerAnswer' => $materialProgress?->trigger_answer ?? 'Belum',

                    'triggerScore' => $materialProgress?->trigger_score ?? 0,

                    'accessTime' => $materialProgress
                        ? gmdate(
                            'H:i:s',
                            $materialProgress->duration_seconds
                        )
                        : '00:00:00',

                    'reflectionAnswer' => $materialProgress?->reflection_answers ?? '[Belum]',

                    'quiz' => $quizAttempt?->score ?? 0,

                    'practiceScore' => $practice?->score ?? 0,

                    'practice' => $practice?->project_url ?? null,

                    'practiceText' => $practice?->submission_text ?? null,

                    'lkpd' => $lkpd?->file_path ?? null,

                    'evaluation' => $evaluation?->answers ?? null,

                    'evaluationScore' => $evaluation?->score ?? 0,

                    'feedback' => $feedbacks->get($student->id)?->feedback ?? '',
                ];
            });

        return Inertia::render(
            'teacher/reports/MeetingDetail',
            [

                'meeting' => [

                    ...$meeting->toArray(),

                    'practiceType' => $meeting->practice?->submission_type,
                ],

                'students' => $students,
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

                    'id' => $student->id,

                    'name' => $student->name,

                    'class' => $student->class,

                    'pretest_score' => $pretest?->score,

                    'posttest_score' => $posttest?->score,

                    'pretest_status' => $pretest
                        ? 'Selesai'
                        : 'Belum',

                    'posttest_status' => $posttest
                        ? 'Selesai'
                        : 'Belum',
                ];
            });

        return Inertia::render(
            'teacher/reports/PrePostReport',
            [

                'students' => $students,
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
                MaterialProgress::where(
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

                    'trigger_score' => $studentData['triggerScore'] ?? 0,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | PRACTICE SUBMISSION
        |--------------------------------------------------------------------------
        */

            $practice =
                PracticeSubmission::where(
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

                    'score' => $studentData['practiceScore'] ?? 0,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | EVALUATION SUBMISSION
        |--------------------------------------------------------------------------
        */

            $evaluation =
                EvaluationSubmission::where(
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

                    'score' => $studentData['evaluationScore'] ?? 0,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | FEEDBACK
        |--------------------------------------------------------------------------
        */

            MeetingFeedback::updateOrCreate(
                [
                    'meeting_id' => $meeting->id,
                    'user_id' => $studentData['id'],
                ],
                [
                    'feedback' => $studentData['feedback'] ?? null,
                ]
            );
        }

        return back()->with(
            'success',
            'Nilai berhasil disimpan.'
        );
    }

    public function export()
    {
        return Excel::download(
            new FullReportExport,
            'laporan-lengkap.xlsx'
        );
    }

    public function exportAssessment()
    {
        return Excel::download(
            new PrePostReportExport,
            'laporan-pretest-posttest.xlsx'
        );
    }

    public function exportMeetingDetail(
        Meeting $meeting
    ) {

        return Excel::download(
            new MeetingDetailExport($meeting),
            'laporan-'.$meeting->title.'.xlsx'
        );
    }
}
