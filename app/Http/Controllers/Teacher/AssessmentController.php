<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function index($type)
    {
        $assessment = Assessment::with('questions')
            ->where('type', $type)
            ->first();

        return Inertia::render(
            'teacher/assessments/Index',
            [
                'type' => $type,

                'assessment' => $assessment,

                'questions' => $assessment->questions,
            ]
        );
    }

    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:pretest,posttest',

            'open_date' => 'required|date',

            'open_time' => 'required',

            'duration' => 'required|integer|min:1|max:300',

            'attempts' => 'required|integer|min:1|max:10',
        ]);

        $assessment = Assessment::where(
            'type',
            $validated['type']
        )->firstOrFail();

        $assessment->update([
            'open_date' => $validated['open_date'],

            'open_time' => $validated['open_time'],

            'duration' => $validated['duration'],

            'attempts' => $validated['attempts'],
        ]);

        return back();
    }
}
