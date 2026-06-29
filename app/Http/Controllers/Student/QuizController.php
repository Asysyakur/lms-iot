<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function exam(
        Meeting $meeting
    ) {

        $quizAvailable =
            $meeting->quizzes()
                ->where('is_active', true)
                ->exists();

        if (! $quizAvailable) {

            return redirect()
                ->route(
                    'student.meeting.show',
                    $meeting->id
                );
        }

        $passedAttempt =
            QuizAttempt::where(
                'user_id',
                auth()->id()
            )
                ->where(
                    'meeting_id',
                    $meeting->id
                )
                ->where(
                    'passed',
                    true
                )
                ->latest()
                ->first();

        if ($passedAttempt) {

            return redirect()->route(
                'student.meeting.quiz.review',
                [
                    'meeting' => $meeting->id,

                    'attempt' => $passedAttempt->id,
                ]
            );
        }

        $questions =
            $meeting->quizzes()
                ->where(
                    'is_active',
                    true
                )
                ->get();

        return Inertia::render(
            'student/meetings/quizzes/Exam',
            [

                'meeting' => $meeting,

                'questions' => $questions,
            ]
        );
    }

    public function submit(
        Request $request,
        Meeting $meeting
    ) {

        $answers =
            $request->answers;

        $questions =
            $meeting->quizzes;

        $correct = 0;

        $attemptCount =
            QuizAttempt::where(
                'user_id',
                auth()->id()
            )
                ->where(
                    'meeting_id',
                    $meeting->id
                )
                ->count();

        if ($attemptCount >= 3) {

            return response()->json([
                'message' => 'Kesempatan quiz habis.',
            ], 403);
        }

        $attempt =
            QuizAttempt::create([

                'user_id' => auth()->id(),

                'meeting_id' => $meeting->id,

                'total_questions' => $questions->count(),

                'attempt_number' => $attemptCount + 1,
            ]);

        foreach ($questions as $question) {

            $selected =
                $answers[$question->id]
                ?? null;

            $isCorrect =
                strtoupper($selected) ===
                strtoupper($question->answer);

            if ($isCorrect) {
                $correct++;
            }

            QuizAnswer::create([

                'quiz_attempt_id' => $attempt->id,

                'quiz_id' => $question->id,

                'selected_answer' => $selected,

                'is_correct' => $isCorrect,
            ]);
        }

        $score = round(
            ($correct / $questions->count()) * 100
        );

        $attempt->update([

            'score' => $score,

            'correct_answers' => $correct,

            'passed' => $score >= 80,

            'submitted_at' => now(),
        ]);

        return response()->json([

            'attempt_id' => $attempt->id,

            'score' => $score,

            'passed' => $score >= 80,
        ]);
    }

    public function review(
        Meeting $meeting,
        QuizAttempt $attempt
    ) {

        $attempt->load([
            'answers.quiz',
        ]);

        $reviews =
            $attempt->answers->map(function ($answer) {

                $studentAnswer =
                    $answer->selected_answer;

                $correctAnswer =
                    $answer->quiz->answer;

                return [

                    'question' => $answer->quiz->question,

                    'code' => $answer->quiz->code,

                    'student' => $studentAnswer
                        ? $studentAnswer.'. '.
                        $answer->quiz->{'option_'.strtolower($studentAnswer)}
                        : '-',

                    'correct' => $correctAnswer.'. '.
                        $answer->quiz->{'option_'.strtolower($correctAnswer)},

                    'isCorrect' => $answer->is_correct,
                ];
            });

        return Inertia::render(
            'student/meetings/quizzes/Review',
            [

                'meetingId' => $meeting->id,

                'score' => $attempt->score,

                'correctAnswers' => $attempt->correct_answers,

                'wrongAnswers' => $attempt->total_questions -
                    $attempt->correct_answers,

                'attempts' => $attempt->attempt_number,

                'reviews' => $reviews,
            ]
        );
    }
}
