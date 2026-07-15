<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Support\SchoolClasses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function index(Request $request, $type)
    {
        $classOptions = SchoolClasses::options();

        $selectedClass = $request->query('class');

        if ($selectedClass === '') {
            $selectedClass = null;
        }

        if (
            $selectedClass !== null
            && ! in_array($selectedClass, $classOptions, true)
        ) {
            $selectedClass = null;
        }

        $assessment = Assessment::with('questions')
            ->where('type', $type)
            ->when(
                $selectedClass === null,
                fn($query) => $query->whereNull('target_class'),
                fn($query) => $query->where(
                    'target_class',
                    $selectedClass
                )
            )
            ->first();

        if (! $assessment) {
            $assessment = new Assessment([
                'type' => $type,
                'target_class' => $selectedClass,
                'title' => Assessment::defaultTitle(
                    $type,
                    $selectedClass
                ),
                'open_date' => now()->toDateString(),
                'open_time' => '08:00',
                'duration' => 30,
                'attempts' => 1,
            ]);

            $assessment->setRelation(
                'questions',
                collect()
            );
        }

        return Inertia::render(
            'teacher/assessments/Index',
            [
                'type' => $type,

                'assessment' => $assessment,

                'questions' => $assessment->questions,
                'classOptions' => $classOptions,
                'selectedClass' => $selectedClass ?? '',
            ]
        );
    }

    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:pretest,posttest',
            'target_class' => ['nullable', Rule::in(SchoolClasses::options())],

            'open_date' => 'required|date',

            'open_time' => 'required',

            'duration' => 'required|integer|min:1|max:300',

            'attempts' => 'required|integer|min:1|max:10',
        ]);

        $assessment = Assessment::firstOrNew([
            'type' => $validated['type'],
            'target_class' => $validated['target_class'] ?? null,
        ]);

        if (! $assessment->exists) {
            $assessment->title = Assessment::defaultTitle(
                $validated['type'],
                $validated['target_class'] ?? null
            );
        }

        $assessment->fill([
            'target_class' => $validated['target_class'] ?? null,
            'open_date' => $validated['open_date'],

            'open_time' => $validated['open_time'],

            'duration' => $validated['duration'],

            'attempts' => $validated['attempts'],
        ]);

        $assessment->save();

        return back();
    }
}
