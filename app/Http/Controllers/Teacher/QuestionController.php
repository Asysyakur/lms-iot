<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $assessment = Assessment::where(
            'type',
            $request->type
        )->first();

        Question::create([
            'assessment_id' => $assessment->id,

            'question' => $request->question,

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
        $question->update([
            'question' => $request->question,

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
