<?php

use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\LkpdController;
use App\Http\Controllers\Teacher\MaterialController;
use App\Http\Controllers\Teacher\MeetingController;
use App\Http\Controllers\Teacher\PracticeController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Teacher\ReportController;
use App\Http\Controllers\Teacher\StudentController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return Inertia::render('Welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | REDIRECT AFTER LOGIN
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function (Request $request) {

        $user = $request->user();

        if (
            $user->role === 'teacher' ||
            $user->role === 'admin'
        ) {
            return redirect()
                ->route('teacher.dashboard');
        }

        return redirect()
            ->route('student.dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:student',
])->prefix('student')->group(function () {
    $assessmentFor = function (string $type): array {
        abort_unless(in_array($type, ['pretest', 'posttest']), 404);

        return $type === 'pretest' ? [
            'type' => 'pretest',
            'title' => 'Pre-test',
            'description' => 'Uji pemahaman awal sebelum memulai pembelajaran.',
            'duration' => 30,
            'total_questions' => 20,
            'essay_questions' => 5,
            'unlocked' => true,
            'requirements' => [
                ['title' => 'Sudah login sebagai siswa', 'completed' => true],
                ['title' => 'Sudah masuk ke kelas', 'completed' => true],
                ['title' => 'Koneksi internet stabil', 'completed' => true],
            ],
        ] : [
            'type' => 'posttest',
            'title' => 'Post-test',
            'description' => 'Uji pemahaman akhir setelah semua materi selesai.',
            'duration' => 45,
            'total_questions' => 25,
            'essay_questions' => 5,
            'unlocked' => false,
            'requirements' => [
                ['title' => 'Pre-test selesai', 'completed' => false],
                ['title' => 'Seluruh materi dibuka', 'completed' => false],
                ['title' => 'Nilai latihan memenuhi ambang', 'completed' => false],
            ],
        ];
    };

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        return Inertia::render(
            'student/dashboard/Index'
        );
    })->name('student.dashboard');

    /*
    |--------------------------------------------------------------------------
    | ASSESSMENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/assessment/{type}',
        fn(string $type) => Inertia::render(
            'student/assessments/Index',
            [
                'assessment' => $assessmentFor($type),
            ]
        )
    )->name('student.assessment');

    Route::get(
        '/assessments/{type}/exam',
        fn(string $type) => Inertia::render(
            'student/assessments/Exam',
            [
                'type' => $assessmentFor($type)['type'],
                'unlocked' => $assessmentFor($type)['unlocked'],
            ]
        )
    )->name('student.assessment.exam');

    Route::get(
        '/assessments/{type}/result',
        fn(string $type) => Inertia::render(
            'student/assessments/Result',
            [
                'type' => $assessmentFor($type)['type'],
            ]
        )
    )->name('student.assessment.result');

    /*
    |--------------------------------------------------------------------------
    | MEETINGS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/meetings/{meeting}',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/Show',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.show');

    Route::get(
        '/meetings/{meeting}/material',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/material/Show',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.materials');

    Route::get(
        '/meetings/{meeting}/quiz',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/quizzes/Start',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.quiz');

    Route::get(
        '/meetings/{meeting}/quiz/exam',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/quizzes/Exam',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.quiz.exam');

    Route::get(
        '/meetings/{meeting}/quiz/review',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/quizzes/Review',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.quiz.Review');

    Route::get(
        '/meetings/{meeting}/practice',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/practices/Show',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.practice');

    Route::get(
        '/meetings/{meeting}/lkpd',
        fn($meeting) =>
        Inertia::render(
            'student/meetings/lkpd/Show',
            [
                'meetingId' => $meeting,
            ]
        )
    )->name('student.meeting.lkpd');
});

/*
|--------------------------------------------------------------------------
| TEACHER / ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:teacher,admin',
])->prefix('teacher')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        return Inertia::render(
            'teacher/dashboard/Index'
        );
    })->name('teacher.dashboard');

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/students', function () {

        return Inertia::render(
            'teacher/students/Index',
            [
                'students' => User::where(
                    'role',
                    'student'
                )->latest()->get(),
            ]
        );
    })->name('teacher.students');

    Route::post('/students', [StudentController::class, 'store'])
        ->name('teacher.students.store');

    Route::delete('/students/{user}', [StudentController::class, 'destroy'])
        ->name('teacher.students.destroy');
    /*
    |--------------------------------------------------------------------------
    | ASSESSMENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/assessments/{type}',
        [AssessmentController::class, 'index']
    );

    Route::post(
        '/assessments/settings',
        [AssessmentController::class, 'saveSettings']
    );

    Route::put(
        '/assessments/settings',
        [AssessmentController::class, 'saveSettings']
    );

    Route::post(
        '/questions',
        [QuestionController::class, 'store']
    );

    Route::put(
        '/questions/{question}',
        [QuestionController::class, 'update']
    );

    Route::delete(
        '/questions/{question}',
        [QuestionController::class, 'destroy']
    );


    /*
    |--------------------------------------------------------------------------
    | MEETINGS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/meetings', function () {

        return Inertia::render(
            'teacher/meetings/Index'
        );
    })->name('teacher.meetings');

    Route::resource(
        'meetings',
        MeetingController::class
    );

    Route::post(
        '/',
        [MeetingController::class, 'store']
    );

    Route::put(
        '/{meeting}',
        [MeetingController::class, 'update']
    );

    Route::delete(
        '/{meeting}',
        [MeetingController::class, 'destroy']
    );

    Route::post(
        '/materials',
        [MaterialController::class, 'store']
    );

    Route::post(
        '/quizzes',
        [QuizController::class, 'store']
    );

    Route::put(
        '/quizzes/{quiz}',
        [QuizController::class, 'update']
    );

    Route::delete(
        '/quizzes/{quiz}',
        [QuizController::class, 'destroy']
    );

    Route::post(
        '/practices',
        [PracticeController::class, 'store']
    );

    Route::post(
        '/lkpds',
        [LkpdController::class, 'store']
    );

    Route::post(
        '/evaluations',
        [EvaluationController::class, 'store']
    );

    Route::patch(
        '/meetings/{meeting}/toggle',
        [MeetingController::class, 'toggle']
    );

    Route::patch(
        '/materials/{material}/toggle',
        [MaterialController::class, 'toggle']
    );

    Route::patch(
        '/quizzes/toggle-by-meeting',
        [QuizController::class, 'toggleByMeeting']
    );

    Route::patch(
        '/practices/{practice}/toggle',
        [PracticeController::class, 'toggle']
    );

    Route::patch(
        '/lkpds/{lkpd}/toggle',
        [LkpdController::class, 'toggle']
    );

    Route::patch(
        '/evaluations/{evaluation}/toggle',
        [EvaluationController::class, 'toggle']
    );

    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', function () {

        return Inertia::render(
            'teacher/reports/Index'
        );
    })->name('teacher.reports');

    Route::get(
        '/reports/meetings',
        [ReportController::class, 'meetings']
    )->name('teacher.reports.meetings');

    Route::get(
        '/reports/assessments',
        [ReportController::class, 'assessments']
    )->name('teacher.reports.assessments');
});

require __DIR__ . '/settings.php';
