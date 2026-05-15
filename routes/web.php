<?php

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
            'teacher/students/Index'
        );

    })->name('teacher.students');

    /*
    |--------------------------------------------------------------------------
    | ASSESSMENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/assessments/{type}',
        fn($type) =>
        Inertia::render(
            'teacher/assessments/Index',
            [
                'type' => $type,
            ]
        )
    )->name('teacher.assessments');

    /*
    |--------------------------------------------------------------------------
    | MEETINGS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/meetings', function () {

        return Inertia::render(
            'teacher/meetings/Show'
        );

    })->name('teacher.meetings');

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
        '/reports/assessments',
        function () {

            return Inertia::render(
                'teacher/reports/PrePostReport'
            );

        }
    )->name('teacher.reports.assessments');

    Route::get(
        '/reports/meetings',
        function () {

            return Inertia::render(
                'teacher/reports/MeetingReport'
            );

        }
    )->name('teacher.reports.meetings');

});

require __DIR__.'/settings.php';