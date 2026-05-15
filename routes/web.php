<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/', 'Dashboard')->name('dashboard');
});

/*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

Route::get('/dashboard', function () {
    return Inertia::render('student/dashboard/Index');
})->name('student.dashboard');

/*
    |--------------------------------------------------------------------------
    | ASSESSMENTS
    |--------------------------------------------------------------------------
    | pretest / posttest
    */

Route::get('/assessment/{type}', function (string $type) {

    abort_unless(
        in_array($type, ['pretest', 'posttest']),
        404
    );

    /*
        |--------------------------------------------------------------------------
        | PRETEST DATA
        |--------------------------------------------------------------------------
        */

    if ($type === 'pretest') {

        return Inertia::render(
            'student/assessments/Index',
            [
                'assessment' => [
                    'type' => 'pretest',
                    'title' => 'Pre-test',
                    'description' => 'Tes awal untuk mengukur pemahaman awal siswa sebelum pembelajaran dimulai.',
                    'duration' => 30,
                    'total_questions' => 20,
                    'essay_questions' => 5,
                    'unlocked' => true,
                ],
            ]
        );
    }

    /*
        |--------------------------------------------------------------------------
        | POSTTEST DATA
        |--------------------------------------------------------------------------
        */

    return Inertia::render(
        'student/assessments/Index',
        [
            'assessment' => [
                'type' => 'posttest',
                'title' => 'Posttest – Evaluasi Akhir',
                'description' => 'Kerjakan setelah menyelesaikan semua aktivitas pada setiap pertemuan.',
                'duration' => 60,
                'total_questions' => 20,
                'essay_questions' => 5,
                'unlocked' => false,

                'requirements' => [
                    [
                        'title' => 'Pre-test sudah dikerjakan',
                        'completed' => true,
                    ],
                    [
                        'title' => 'Semua materi P1–P3 sudah dibuka',
                        'completed' => true,
                    ],
                    [
                        'title' => 'Semua refleksi sudah diisi',
                        'completed' => true,
                    ],
                    [
                        'title' => 'Semua kuis selesai (nilai ≥ 80)',
                        'completed' => true,
                    ],
                    [
                        'title' => 'Semua praktik MakeCode dikumpulkan',
                        'completed' => true,
                    ],
                    [
                        'title' => 'LKPD P2 belum dikumpulkan',
                        'completed' => false,
                    ],
                ],
            ],
        ]
    );
})->name('student.assessment');

/*
    |--------------------------------------------------------------------------
    | EXAM PAGE
    |--------------------------------------------------------------------------
    */

Route::get('/assessments/{type}/exam', function (string $type) {

    abort_unless(
        in_array($type, ['pretest', 'posttest']),
        404
    );

    return Inertia::render(
        'student/assessments/Exam',
        [
            'type' => $type,
        ]
    );
})->name('student.assessment.exam');

/*
    |--------------------------------------------------------------------------
    | RESULT PAGE
    |--------------------------------------------------------------------------
    */

Route::get('/assessments/{type}/result', function (string $type) {

    abort_unless(
        in_array($type, ['pretest', 'posttest']),
        404
    );

    return Inertia::render(
        'student/assessments/Result',
        [
            'type' => $type,
        ]
    );
})->name('student.assessment.result');

Route::get('/meetings/{meeting}', function ($meeting) {

    return Inertia::render(
        'student/meetings/Show',
        [
            'meetingId' => $meeting,
        ]
    );
})->name('student.meeting.show');

Route::get('/meetings/{meeting}/material', function ($meeting) {

    return Inertia::render(
        'student/meetings/material/Show',
        [
            'meetingId' => $meeting,
        ]
    );
})->name('student.meeting.materials');

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
)->name('student.meeting.quiz.review');

Route::get('/meetings/{meeting}/practice', function ($meeting) {

    return Inertia::render(
        'student/meetings/practices/Show',
        [
            'meetingId' => $meeting,
        ]
    );
})->name('student.meeting.practice');

Route::get('/meetings/{meeting}/lkpd', function ($meeting) {

    return Inertia::render(
        'student/meetings/lkpd/Show',
        [
            'meetingId' => $meeting,
        ]
    );
})->name('student.meeting.lkpd');

/*
    |--------------------------------------------------------------------------
    | TEACHER DASHBOARD
    |--------------------------------------------------------------------------
    */

Route::get('/teacher/dashboard', function () {
    return Inertia::render('teacher/dashboard/Index');
})->name('teacher.dashboard');

Route::get('/teacher/students', function () {
    return Inertia::render('teacher/students/Index');
})->name('teacher.students');

Route::get(
    '/teacher/assessments/{type}',
    function ($type) {

        return Inertia::render(
            'teacher/assessments/Index',
            [
                'type' => $type,
            ]
        );
    }
)->name('teacher.assessments');

Route::get('/teacher/meetings', function () {

    return Inertia::render(
        'teacher/meetings/Show'
    );

})->name('teacher.meeting.show');

Route::get('/teacher/reports', function () {
    return Inertia::render('teacher/reports/Index');
})->name('teacher.reports');

Route::get('/teacher/reports/assessments', function () {
    return Inertia::render('teacher/reports/PrePostReport');
})->name('teacher.reports.assessments');

Route::get('/teacher/reports/meetings', function () {
    return Inertia::render('teacher/reports/MeetingReport');
})->name('teacher.reports.meetings');

require __DIR__ . '/settings.php';
