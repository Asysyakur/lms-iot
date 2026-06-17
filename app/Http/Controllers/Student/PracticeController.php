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

        $practice = $meeting->practice;

        $type =
            $practice->submission_type
            ?? 'link';

        $rules = [];

        if ($type === 'link') {

            $rules['project_url'] = [

                'required',
                'url',

                function (
                    $attribute,
                    $value,
                    $fail
                ) {

                    if (
                        !str_contains(
                            $value,
                            'makecode.microbit.org'
                        )
                    ) {

                        $fail(
                            'Link harus berasal dari MakeCode.'
                        );
                    }
                }
            ];
        }

        if ($type === 'text') {

            $rules['submission_text'] = [

                'required',
                'string',
                'min:10',
            ];
        }

        if ($type === 'both') {

            $rules['project_url'] = [

                'required',
                'url',

                function (
                    $attribute,
                    $value,
                    $fail
                ) {

                    if (
                        !str_contains(
                            $value,
                            'makecode.microbit.org'
                        )
                    ) {

                        $fail(
                            'Link harus berasal dari MakeCode.'
                        );
                    }
                }
            ];

            $rules['submission_text'] = [

                'required',
                'string',
                'min:10',
            ];
        }

        $request->validate(
            $rules
        );

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

                'submission_text' =>
                $request->submission_text,

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
