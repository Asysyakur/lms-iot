<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Support\SchoolClasses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
                'classOptions' => SchoolClasses::options(),
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'target_class' => [
                'nullable',
                Rule::in(SchoolClasses::options()),
            ],
        ]);

        $meeting = Meeting::create([
            'meeting_number' => $validated['meeting_number'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_class' => $validated['target_class'] ?? null,
        ]);

        return response()->json([
            'meeting' => $meeting,
        ]);
    }

    public function update(
        Request $request,
        Meeting $meeting
    ) {
        $validated = $request->validate([
            'title' => 'required|string',
            'meeting_number' => 'required|integer',
            'description' => 'nullable|string',
            'target_class' => [
                'nullable',
                Rule::in(SchoolClasses::options()),
            ],
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
