<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',

            'question' => 'required|string',

            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',

            'answer' => 'required|in:A,B,C,D',
        ]);

        $quiz = Quiz::create($validated);

        return response()->json([
            'quiz' => $quiz,
        ]);
    }

    /**
     * UPDATE
     */
    public function update(
        Request $request,
        Quiz $quiz
    ) {
        $validated = $request->validate([
            'question' => 'required|string',

            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',

            'answer' => 'required|in:A,B,C,D',
        ]);

        $quiz->update($validated);

        return response()->json([
            'quiz' => $quiz,
        ]);
    }

    /**
     * DESTROY
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function toggleByMeeting(
        Request $request
    ) {
        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
        ]);

        $quizzes = Quiz::where(
            'meeting_id',
            $request->meeting_id
        )->get();

        if ($quizzes->isEmpty()) {
            return response()->json([
                'message' => 'Quiz kosong',
            ], 404);
        }

        $isActive =
            !$quizzes->first()->is_active;

        Quiz::where(
            'meeting_id',
            $request->meeting_id
        )->update([
            'is_active' => $isActive,
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $isActive,
        ]);
    }
}
