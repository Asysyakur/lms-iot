<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EvaluationSubmission;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvaluationController extends Controller
{
    public function show(
        Meeting $meeting
    ) {
        $meeting->load([
            'evaluation',
        ]);

        $submission =
            EvaluationSubmission::where(
                'user_id',
                auth()->id()
            )
            ->where(
                'meeting_id',
                $meeting->id
            )
            ->first();

        return Inertia::render(
            'student/meetings/evaluations/Show',
            [

                'meeting' =>
                $meeting,

                'evaluation' =>
                $meeting->evaluation,

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

            'answers' => [
                'required',
                'array',
                'min:1',
            ],

            'answers.*' => [
                'required',
                'string',
                'min:10',
            ],
        ]);

        EvaluationSubmission::updateOrCreate(

            [

                'user_id' =>
                auth()->id(),

                'meeting_id' =>
                $meeting->id,
            ],

            [

                'answers' =>
                $request->answers,

                'submitted_at' =>
                now(),
            ]
        );

        return response()->json([

            'message' =>
            'Evaluasi berhasil dikumpulkan',
        ]);
    }
}
