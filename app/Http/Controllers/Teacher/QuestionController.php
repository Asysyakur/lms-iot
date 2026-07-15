<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use App\Support\SchoolClasses;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:pretest,posttest'],
            'target_class' => ['nullable', Rule::in(SchoolClasses::options())],
        ]);

        $assessment = Assessment::where(
            'type',
            $validated['type']
        )
            ->when(
                ($validated['target_class'] ?? null) === null,
                fn($query) => $query->whereNull('target_class'),
                fn($query) => $query->where(
                    'target_class',
                    $validated['target_class']
                )
            )
            ->first();

        if (! $assessment) {
            $assessment = Assessment::create([
                'type' => $validated['type'],
                'target_class' => $validated['target_class'] ?? null,
                'title' => Assessment::defaultTitle(
                    $validated['type'],
                    $validated['target_class'] ?? null
                ),
                'open_date' => now()->toDateString(),
                'open_time' => '08:00',
                'duration' => 30,
                'attempts' => 1,
            ]);
        }

        Question::create([
            'assessment_id' => $assessment->id,

            'question' => $request->question,

            'code' => $request->code,

            'type' => $request->question_type,

            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,

            'answer' => $request->answer,

            'is_active' => true,
        ]);

        return back();
    }

    public function update(
        Request $request,
        Question $question
    ) {
        $request->validate([
            'type' => ['required', 'in:pretest,posttest'],
        ]);

        $question->update([
            'question' => $request->question,

            'code' => $request->code,

            'type' => $request->question_type,

            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,

            'answer' => $request->answer,
        ]);

        return back();
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return back();
    }
}
