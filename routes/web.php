<?php

use App\Http\Controllers\Student\AssessmentController as StudentAssessmentController;
use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\LkpdController;
use App\Http\Controllers\Teacher\MaterialController;
use App\Http\Controllers\Teacher\MeetingController;
use App\Http\Controllers\Student\MeetingController as StudentMeetingController;
use App\Http\Controllers\Student\MeetingMaterialController;
use App\Http\Controllers\Teacher\PracticeController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
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
    'role:student,teacher,admin'
])->prefix('student')->group(function () {

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
        '/assessments/{type}',
        [StudentAssessmentController::class, 'index']
    );

    Route::get(
        '/assessments/{type}/exam',
        [StudentAssessmentController::class, 'exam']
    );

    Route::post(
        '/assessments/answer',
        [StudentAssessmentController::class, 'saveAnswer']
    );

    Route::post(
        '/assessments/submit',
        [StudentAssessmentController::class, 'submit']
    );

    Route::get(
        '/assessments/{type}/result',
        [StudentAssessmentController::class, 'result']
    );


    /*
    |--------------------------------------------------------------------------
    | MEETINGS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/meetings/{meeting}',
        [StudentMeetingController::class, 'show']
    )->name('student.meeting.show');

    Route::get(
        '/meetings/{meeting}/material',
        [MeetingMaterialController::class, 'show']
    )->name('student.meeting.materials');

    Route::post(
        '/meetings/{meeting}/material/progress',
        [MeetingMaterialController::class, 'saveProgress']
    )->name('student.material.progress');

    Route::get(
        '/meetings/{meeting}/quiz',
        [StudentQuizController::class, 'exam']
    )->name('student.meeting.quiz');

    Route::post(
        '/meetings/{meeting}/quiz/submit',
        [StudentQuizController::class, 'submit']
    )->name('student.meeting.quiz.submit');

    Route::get(
        '/meetings/{meeting}/quiz/review/{attempt}',
        [StudentQuizController::class, 'review']
    )->name('student.meeting.quiz.review');

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
