<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Meeting;

use Inertia\Inertia;

class MeetingController extends Controller
{
    public function show(Meeting $meeting)
    {
        $steps = [
            [
                'id' => 1,

                'title' => 'Materi',

                'description' =>
                'Pelajari materi tentang konsep dasar IoT dan pengenalan Micro:bit.',

                'icon' => 'BookOpen',

                'unlocked' => true,

                'completed' => false,

                'active' => true,

                'href' =>
                "/student/meetings/{$meeting->id}/material",
            ],

            [
                'id' => 2,

                'title' => 'Kuis',

                'description' =>
                'Kerjakan kuis untuk menguji pemahaman.',

                'icon' => 'ClipboardCheck',

                'unlocked' => false,

                'completed' => false,

                'active' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/quiz",
            ],

            [
                'id' => 3,

                'title' => 'Praktik Mandiri',

                'description' =>
                'Lakukan praktik Micro:bit.',

                'icon' => 'Code2',

                'unlocked' => false,

                'completed' => false,

                'active' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/practice",
            ],

            [
                'id' => 4,

                'title' => 'LKPD',

                'description' =>
                'Lembar kerja peserta didik.',

                'icon' => 'FileSpreadsheet',

                'unlocked' => false,

                'completed' => false,

                'active' => false,

                'teacher_only' => true,
            ],
        ];

        return Inertia::render(
            'student/meetings/Show',
            [
                'meeting' => [
                    'id' => $meeting->id,

                    'title' => $meeting->title,

                    'subtitle' => $meeting->subtitle,

                    'description' => $meeting->description,

                    'opened' =>
                    $meeting->opened_at &&
                        $meeting->closed_at
                        ? now()->between(
                            $meeting->opened_at,
                            $meeting->closed_at
                        )
                        : false,

                    'opened_at' =>
                    $meeting->opened_at
                        ? $meeting->opened_at->format(
                            'd M Y H:i'
                        )
                        : '-',

                    'closed_at' =>
                    $meeting->closed_at
                        ? $meeting->closed_at->format(
                            'd M Y H:i'
                        )
                        : '-',
                ],

                'steps' => $steps,
            ]
        );
    }
}
