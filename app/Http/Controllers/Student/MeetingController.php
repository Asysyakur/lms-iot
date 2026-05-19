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
                'description' => 'Pelajari materi...',
                'icon' => 'BookOpen',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->material?->is_active
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->material?->is_active
                ),

                'completed' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/material",
            ],

            [
                'id' => 2,
                'title' => 'Kuis',
                'description' => 'Kerjakan kuis...',
                'icon' => 'ClipboardCheck',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->quizzes()
                    ->where('is_active', true)
                    ->exists()
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->quizzes()
                    ->where('is_active', true)
                    ->exists()
                    &&
                    $meeting->materialProgress()
                    ->where('user_id', auth()->id())
                    ->where('reflection_completed', true)
                    ->exists()
                ),

                'completed' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/quiz",
            ],

            [
                'id' => 3,
                'title' => 'Praktik Mandiri',
                'description' => 'Lakukan praktik...',
                'icon' => 'Code2',

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->practice?->is_active
                ),

                'unlocked' => (
                    $meeting->is_active
                    &&
                    $meeting->practice?->is_active
                ),

                'completed' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/practice",
            ],

            [
                'id' => 4,
                'title' => 'LKPD',
                'description' => 'Kerjakan LKPD...',
                'icon' => 'FileSpreadsheet',

                'teacher_only' => true,

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->lkpd?->is_active
                ),

                'unlocked' => false,

                'completed' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/lkpd",
            ],
            
            [
                'id' => 5,
                'title' => 'Evaluasi',
                'description' => 'Kerjakan evaluasi...',
                'icon' => 'ClipboardList',

                'teacher_only' => true,

                'active' => (
                    $meeting->is_active
                    &&
                    $meeting->evaluation?->is_active
                ),

                'unlocked' => false,

                'completed' => false,

                'href' =>
                "/student/meetings/{$meeting->id}/evaluation",
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
