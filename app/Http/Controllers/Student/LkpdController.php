<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LkpdSubmission;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LkpdController extends Controller
{
    public function show(
        Meeting $meeting
    ) {
        abort_unless(
            $meeting->isAccessibleTo(auth()->user()?->class),
            403
        );

        $meeting->load([
            'lkpd',
            'evaluation',
        ]);

        if (
            !$meeting->lkpd ||
            !$meeting->lkpd->is_active
        ) {

            return redirect()
                ->route(
                    'student.meeting.show',
                    $meeting->id
                );
        }

        $submission =
            LkpdSubmission::where(
                'user_id',
                auth()->id()
            )
            ->where(
                'meeting_id',
                $meeting->id
            )
            ->first();

        return Inertia::render(
            'student/meetings/lkpd/Show',
            [

                'meeting' =>
                $meeting,

                'lkpd' =>
                $meeting->lkpd,

                'submission' =>
                $submission,
            ]
        );
    }

    public function submit(
        Request $request,
        Meeting $meeting
    ) {
        abort_unless(
            $meeting->isAccessibleTo(auth()->user()?->class),
            403
        );

        $request->validate([

            'file' => [

                'required',

                'file',

                'mimes:pdf,doc,docx',

                'max:10240',
            ],
        ]);

        $file =
            $request->file('file');

        $path =
            $file->store(
                'lkpd',
                'public'
            );

        LkpdSubmission::updateOrCreate(

            [

                'user_id' =>
                auth()->id(),

                'meeting_id' =>
                $meeting->id,
            ],

            [

                'file_path' =>
                $path,

                'original_name' =>
                $file->getClientOriginalName(),

                'mime_type' =>
                $file->getMimeType(),

                'file_size' =>
                $file->getSize(),

                'submitted_at' =>
                now(),
            ]
        );

        return response()->json([

            'message' =>
            'LKPD berhasil dikumpulkan',
        ]);
    }
}
