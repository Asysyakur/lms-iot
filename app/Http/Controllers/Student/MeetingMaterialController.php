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
        $progress = MaterialProgress::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'meeting_id' => $meeting->id,
            ]
        );

        $progress->update([
            'reading_progress' =>
            $request->has('reading_progress')
                ? $request->reading_progress
                : $progress->reading_progress,

            'trigger_answer' =>
            $request->has('trigger_answer')
                ? $request->trigger_answer
                : $progress->trigger_answer,

            'reflection_answers' =>
            $request->has('reflection_answers')
                ? $request->reflection_answers
                : $progress->reflection_answers,

            'reflection_completed' =>
            $request->has('reflection_completed')
                ? $request->reflection_completed
                : $progress->reflection_completed,
        ]);

        return back();
    }
}
