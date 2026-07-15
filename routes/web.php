<?php

use App\Http\Controllers\Student\AssessmentController as StudentAssessmentController;
use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Student\EvaluationController as StudentEvaluationController;
use App\Http\Controllers\Teacher\LkpdController;
use App\Http\Controllers\Student\LkpdController as StudentLkpdController;
use App\Http\Controllers\Teacher\MaterialController;
use App\Http\Controllers\Teacher\MeetingController;
use App\Http\Controllers\Student\MeetingController as StudentMeetingController;
use App\Http\Controllers\Student\MeetingMaterialController;
use App\Http\Controllers\Teacher\PracticeController;
use App\Http\Controllers\Student\PracticeController as StudentPracticeController;
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

    if (auth()->check()) {

        $user = auth()->user();

        /**
         * GURU / ADMIN
         */
        if (
            $user->role === 'teacher'
            ||
            $user->role === 'admin'
        ) {

            return redirect()
                ->route(
                    'teacher.dashboard'
                );
        }

        /**
         * SISWA
         */
        return redirect()
            ->route(
                'student.dashboard'
            );
    }

    /**
     * BELUM LOGIN
     */
    return redirect('/login');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

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

    Route::get('/dashboard', [
        StudentDashboardController::class,
        'index',
    ])->name('student.dashboard');
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

    Route::post(
        '/meetings/{meeting}/start-reading',
        [MeetingMaterialController::class, 'startReading']
    );

    Route::post(
        '/meetings/{meeting}/heartbeat',
        [MeetingMaterialController::class, 'heartbeat']
    );

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
        [StudentPracticeController::class, 'show']
    );

    Route::post(
        '/meetings/{meeting}/practice/submit',
        [StudentPracticeController::class, 'submit']
    );

    Route::get(
        '/meetings/{meeting}/lkpd',
        [StudentLkpdController::class, 'show']
    );

    Route::post(
        '/meetings/{meeting}/lkpd/submit',
        [StudentLkpdController::class, 'submit']
    );

    Route::get(
        '/meetings/{meeting}/evaluation',
        [StudentEvaluationController::class, 'show']
    );

    Route::post(
        '/meetings/{meeting}/evaluation/submit',
        [StudentEvaluationController::class, 'submit']
    );
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

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('teacher.dashboard');

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/students', function () {

        return Inertia::render(
            'teacher/students/Index',
            [
                'students' => User::whereIn(
                    'role',
                    [
                        'student',
                        'teacher',
                    ]
                )
                    ->latest()
                    ->get([
                        'id',
                        'name',
                        'class',
                        'username',
                        'role',
                        'last_seen_at',
                    ]),
            ]
        );
    })->name('teacher.students');

    Route::post('/students', [StudentController::class, 'store'])
        ->name('teacher.students.store');

    Route::put(
        '/students/{user}',
        [StudentController::class, 'update']
    );

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

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    );

    Route::get(
        '/reports/meetings',
        [ReportController::class, 'meetings']
    )->name('teacher.reports.meetings');

    Route::get(
        '/reports/meetings/{meeting}',
        [ReportController::class, 'meetingDetail']
    )->name('teacher.reports.meeting.detail');

    Route::post(
        '/reports/meetings/{meeting}/scores',
        [ReportController::class, 'saveScores']
    )->name(
        'teacher.reports.meetings.scores'
    );

    Route::get(
        '/reports/export',
        [ReportController::class, 'export']
    )->name('teacher.reports.export');

    Route::get(
        '/reports/assessments/export',
        [ReportController::class, 'exportAssessment']
    );

    Route::get(
        '/reports/meetings/{meeting}/export',
        [ReportController::class, 'exportMeetingDetail']
    );

    Route::get(
        '/reports/assessments',
        [ReportController::class, 'assessments']
    )->name('teacher.reports.assessments');
});

require __DIR__ . '/settings.php';
