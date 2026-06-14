<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Inertia\Inertia;
use App\Models\MaterialProgress;
use Illuminate\Http\Request;

class MeetingMaterialController extends Controller
{
    public function show(Meeting $meeting)
    {
        $meeting->load('material');

        if (
            !$meeting->material ||
            !$meeting->material->is_active
        ) {

            return redirect()
                ->route(
                    'student.meeting.show',
                    $meeting->id
                );
        }

        $progress = MaterialProgress::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'meeting_id' => $meeting->id,
            ]
        );

        return Inertia::render(
            'student/meetings/material/Show',
            [
                'progress' => $progress,

                'meeting' => [
                    'id' => $meeting->id,

                    'title' => $meeting->title,

                    'subtitle' =>
                    $meeting->subtitle,

                    'description' =>
                    $meeting->description,
                ],

                'material' => [
                    'title' =>
                    $meeting->material?->title,

                    'description' =>
                    $meeting->material?->description,

                    'pdf_url' =>
                    $meeting->material?->pdf_file
                        ? asset(
                            'storage/' .
                                $meeting->material->pdf_file
                        )
                        : null,

                    'video_url' =>
                    $meeting->material?->video_url,

                    'trigger_question' =>
                    $meeting->material?->trigger_question,

                    'reflection_questions' =>
                    $meeting->material?->reflection_question ?? [],
                ],
            ]
        );
    }

    public function saveProgress(
        Request $request,
        Meeting $meeting
    ) {

        $progress =
            MaterialProgress::firstOrCreate([
                'user_id' => auth()->id(),
                'meeting_id' => $meeting->id,
            ]);

        /**
         * READING PROGRESS
         */
        if (
            $request->filled(
                'reading_progress'
            )
        ) {

            $progress->reading_progress =
                max(
                    $progress->reading_progress ?? 0,
                    $request->reading_progress
                );
        }

        /**
         * TRIGGER ANSWER
         */
        if (
            $request->filled(
                'trigger_answer'
            )
        ) {

            $progress->trigger_answer =
                $request->trigger_answer;
        }

        /**
         * REFLECTION
         */
        if (
            $request->has(
                'reflection_answers'
            )
        ) {

            $progress->reflection_answers =
                $request->reflection_answers;

            $progress->reflection_completed =
                $request->reflection_completed;
        }

        $progress->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function startReading(
        Meeting $meeting
    ) {

        MaterialProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),

                'meeting_id' => $meeting->id,
            ],
            [
                'started_at' => now(),

                'last_activity_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
        ]);
    }

    public function heartbeat(
        Meeting $meeting
    ) {

        $progress =
            MaterialProgress::firstOrCreate(
                [
                    'user_id' => auth()->id(),

                    'meeting_id' => $meeting->id,
                ]
            );

        $progress->increment(
            'duration_seconds',
            30
        );

        $progress->update([
            'last_activity_at' => now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
