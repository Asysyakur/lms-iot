<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\PracticeSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PracticeController extends Controller
{
    public function show(Meeting $meeting)
    {
        $meeting->load([
            'practice',
            'lkpd',
        ]);

        if (
            !$meeting->practice ||
            !$meeting->practice->is_active
        ) {

            return redirect()
                ->route(
                    'student.meeting.show',
                    $meeting->id
                );
        }

        $practice =
            $meeting->practice;

        $submission =
            PracticeSubmission::where(
                'user_id',
                auth()->id()
            )
            ->where(
                'meeting_id',
                $meeting->id
            )
            ->first();
        return Inertia::render(
            'student/meetings/practices/Show',
            [

                'meeting' =>
                $meeting,

                'practice' =>
                $practice,

                'submission' =>
                $submission,
            ]
        );
    }

    public function submit(
        Request $request,
        Meeting $meeting
    ) {

        $request->validate([

            'project_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {

                    if (
                        !str_contains(
                            $value,
                            'makecode.microbit.org'
                        )
                    ) {

                        $fail(
                            'Link harus berasal dari MakeCode Microbit.'
                        );
                    }
                }
            ],
        ]);

        PracticeSubmission::updateOrCreate(

            [

                'user_id' =>
                auth()->id(),

                'meeting_id' =>
                $meeting->id,
            ],

            [

                'project_url' =>
                $request->project_url,

                'submitted_at' =>
                now(),
            ]
        );

        return response()->json([

            'message' =>
            'Praktik berhasil dikumpulkan',
        ]);
    }
}
