<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with([
            'material',
            'quizzes',
            'practice',
            'lkpd',
            'evaluation',
        ])->get();

        $meetings->each(function ($meeting) {

            if ($meeting->material) {

                $meeting->material->pdf_url =
                    $meeting->material->pdf_file
                    ? asset(
                        'storage/' .
                            $meeting->material->pdf_file
                    )
                    : null;
            }
        });

        return Inertia::render(
            'teacher/meetings/Index',
            [
                'meetings' => $meetings,
            ]
        );
    }

    public function store(Request $request)
    {
        $meeting = Meeting::create([
            'meeting_number' => $request->meeting_number,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'meeting' => $meeting,
        ]);
    }

    public function update(
        Request $request,
        Meeting $meeting
    ) {
        $validated =
            $request->validate([
                'title' =>
                'required|string',

                'meeting_number' =>
                'required|integer',
                'description' =>
                'nullable|string',
            ]);

        $meeting->update(
            $validated
        );

        return response()->json([
            'meeting' => $meeting,
        ]);
    }

    public function destroy(
        Meeting $meeting
    ) {
        $meeting->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function toggle(Meeting $meeting)
    {
        $meeting->update([
            'is_active' =>
            !$meeting->is_active,
        ]);

        return response()->json([
            'meeting' => $meeting,
        ]);
    }
}
