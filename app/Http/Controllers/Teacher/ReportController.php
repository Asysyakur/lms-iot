<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\User;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * REPORT PERTEMUAN
     */
    public function meetings()
    {
        $meetings = Meeting::with([
            'material',
            'quizzes',
            'practice',
            'lkpd',
            'evaluation',
        ])->get();

        return Inertia::render(
            'teacher/reports/MeetingReport',
            [
                'meetings' => $meetings,
            ]
        );
    }

    /**
     * REPORT PRETEST / POSTTEST
     */
    public function assessments()
    {
        $students = User::where(
            'role',
            'student'
        )->get();

        return Inertia::render(
            'teacher/reports/PrePostReport',
            [
                'students' => $students,
            ]
        );
    }
}
