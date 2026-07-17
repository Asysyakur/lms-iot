<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    private function resolveAssessment(string $type): Assessment
    {
        $assessment = Assessment::with('questions')
            ->where('type', $type)
            ->forStudentClass(auth()->user()?->class)
            ->firstOrFail();

        abort_unless(
            $assessment->isAccessibleTo(auth()->user()?->class),
            403
        );

        return $assessment;
    }

    /*
    |--------------------------------------------------------------------------
    | EXAM PAGE
    |--------------------------------------------------------------------------
    */

    public function index($type)
    {
        $assessment = $this->resolveAssessment($type);

        $results = AssessmentResult::where(
            'assessment_id',
            $assessment->id
        )
            ->where(
                'student_id',
                Auth::id()
            )
            ->get();

        $submittedCount = $results
            ->where('status', 'submitted')
            ->count();

        $latestResult = $results
            ->where('status', 'submitted')
            ->sortByDesc('created_at')
            ->first();

        $ongoingResult =
            $results
            ->where(
                'status',
                'in_progress'
            )
            ->sortByDesc('created_at')
            ->first();

        $otherOngoingAssessment =
            AssessmentResult::with(
                'assessment'
            )
            ->where(
                'student_id',
                Auth::id()
            )
            ->where(
                'status',
                'in_progress'
            )
            ->whereHas(
                'assessment',
                function ($query) use ($type) {

                    $query->where(
                        'type',
                        '!=',
                        $type
                    );
                }
            )
            ->latest()
            ->first();

        /*
    |--------------------------------------------------------------------------
    | CHECK OPEN DATETIME
    |--------------------------------------------------------------------------
    */

        $openAt = Carbon::parse(
            $assessment->open_date . ' ' . $assessment->open_time
        );

        $isOpened = now()->gte($openAt);

        /*
    |--------------------------------------------------------------------------
    | ATTEMPT
    |--------------------------------------------------------------------------
    */

        $remainingAttempts =
            max(
                0,
                $assessment->attempts -
                    $submittedCount
            );

        $canTakeExam =
            $isOpened &&
            $remainingAttempts > 0;

        return Inertia::render(
            'student/assessments/Index',
            [
                'assessment' => [
                    ...$assessment->toArray(),

                    'total_questions' =>
                    $assessment
                        ->questions
                        ->count(),

                    'essay_questions' => 0,
                ],

                'latestResult' =>
                $latestResult,

                'ongoingResult' =>
                $ongoingResult,

                'otherOngoingAssessment' =>
                $otherOngoingAssessment,

                'submittedCount' =>
                $submittedCount,

                'remainingAttempts' =>
                $remainingAttempts,

                'isOpened' =>
                $isOpened,

                'canTakeExam' =>
                $canTakeExam,

                'openAt' =>
                $openAt->format(
                    'd M Y H:i'
                ),
            ]
        );
    }

    public function exam($type)
    {
        $assessment = $this->resolveAssessment($type);

        /*
    |--------------------------------------------------------------------------
    | CHECK OPEN TIME
    |--------------------------------------------------------------------------
    */

        $openAt = Carbon::parse(
            $assessment->open_date . ' ' . $assessment->open_time
        );

        if (
            now()->lessThan($openAt)
        ) {
            return redirect(
                "/student/assessments/{$type}"
            );
        }

        /*
    |--------------------------------------------------------------------------
    | CHECK ATTEMPTS
    |--------------------------------------------------------------------------
    */

        $submittedCount = AssessmentResult::where(
            'assessment_id',
            $assessment->id
        )
            ->where(
                'student_id',
                Auth::id()
            )
            ->where(
                'status',
                'submitted'
            )
            ->count();

        if (
            $submittedCount >=
            $assessment->attempts
        ) {
            return redirect(
                "/student/assessments/{$type}"
            );
        }

        /*
    |--------------------------------------------------------------------------
    | CONTINUE EXISTING EXAM
    |--------------------------------------------------------------------------
    */

        $result = AssessmentResult::where(
            'assessment_id',
            $assessment->id
        )
            ->where(
                'student_id',
                Auth::id()
            )
            ->where(
                'status',
                'in_progress'
            )
            ->latest()
            ->first();

        /*
|--------------------------------------------------------------------------
| AUTO SUBMIT IF EXPIRED
|--------------------------------------------------------------------------
*/

        if ($result) {

            $expiredAt =
                Carbon::parse(
                    $result->started_at
                )->addMinutes(
                    $assessment->duration
                );

            if (
                now()->greaterThan(
                    $expiredAt
                )
            ) {

                $correct =
                    $result->answers
                    ->where('is_correct', true)
                    ->count();

                $wrong =
                    $result->answers
                    ->where('is_correct', false)
                    ->count();

                $total =
                    $assessment->questions
                    ->count();

                $score =
                    $total > 0
                    ? round(
                        ($correct / $total) * 100
                    )
                    : 0;

                $result->update([
                    'score' =>
                    $score,

                    'correct_answers' =>
                    $correct,

                    'wrong_answers' =>
                    $wrong,

                    'submitted_at' =>
                    now(),

                    'status' =>
                    'submitted',
                ]);

                return redirect(
                    "/student/assessments/{$type}/result"
                );
            }
        }
        /*
    |--------------------------------------------------------------------------
    | CREATE NEW ATTEMPT
    |--------------------------------------------------------------------------
    */

        if (!$result) {

            $result = AssessmentResult::create([
                'assessment_id' =>
                $assessment->id,

                'student_id' =>
                Auth::id(),

                'started_at' => now(),

                'status' =>
                'in_progress',
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | LOAD ANSWERS
    |--------------------------------------------------------------------------
    */

        $answers = AssessmentAnswer::where(
            'assessment_result_id',
            $result->id
        )->get();

        return Inertia::render(
            'student/assessments/Exam',
            [
                'assessment' => [

                    ...$assessment->toArray(),

                    'duration' =>
                    $assessment->duration,
                ],

                'questions' =>
                $assessment->questions,

                'result' => $result,

                'savedAnswers' =>
                $answers,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE ANSWER
    |--------------------------------------------------------------------------
    */

    public function saveAnswer(Request $request)
    {
        $validated = $request->validate([
            'assessment_result_id' => 'required',

            'question_id' => 'required',

            'answer' => 'required|in:A,B,C,D',
        ]);

        $question = Question::findOrFail(
            $validated['question_id']
        );

        $result = AssessmentResult::where(
            'id',
            $validated['assessment_result_id']
        )
            ->where(
                'student_id',
                Auth::id()
            )
            ->firstOrFail();

        abort_unless(
            $result->assessment_id === $question->assessment_id,
            403
        );

        $isCorrect =
            $validated['answer'] === $question->answer;

        $answer = AssessmentAnswer::updateOrCreate(
            [
                'assessment_result_id' =>
                $result->id,

                'question_id' =>
                $validated['question_id'],
            ],
            [
                'answer' => $validated['answer'],

                'is_correct' => $isCorrect,
            ]
        );

        return response()->json([
            'answer' => $answer,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'result_id' => 'required',
        ]);

        $result = AssessmentResult::with([
            'answers',
            'assessment.questions',
        ])
            ->where(
                'id',
                $validated['result_id']
            )
            ->where(
                'student_id',
                Auth::id()
            )
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | PREVENT DOUBLE SUBMIT
    |--------------------------------------------------------------------------
    */

        if (
            $result->status === 'submitted'
        ) {

            return response()->json([
                'message' =>
                'Assessment sudah selesai.'
            ], 403);
        }

        $totalQuestions = $result->assessment->questions->count();
        $answeredQuestions = $result->answers
            ->pluck('question_id')
            ->unique()
            ->count();

        if ($answeredQuestions < $totalQuestions) {
            return response()->json([
                'message' => 'Semua soal harus dijawab sebelum assessment dikumpulkan.',
            ], 422);
        }

        $correct =
            $result->answers
            ->where('is_correct', true)
            ->count();

        $wrong =
            $result->answers
            ->where('is_correct', false)
            ->count();

        $total =
            $correct + $wrong;

        $score =
            $total > 0
            ? round(($correct / $total) * 100)
            : 0;

        $result->update([
            'score' => $score,

            'correct_answers' => $correct,

            'wrong_answers' => $wrong,

            'submitted_at' => now(),

            'status' => 'submitted',
        ]);

        return response()->json([
            'result' => $result,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RESULT PAGE
    |--------------------------------------------------------------------------
    */

    public function result($type)
    {
        $assessment = $this->resolveAssessment($type);

        $result = AssessmentResult::with([
            'answers.question',
        ])
            ->where(
                'assessment_id',
                $assessment->id
            )
            ->where(
                'student_id',
                Auth::id()
            )
            ->where(
                'status',
                'submitted'
            )
            ->latest('submitted_at')
            ->firstOrFail();

        return Inertia::render(
            'student/assessments/Result',
            [
                'assessment' => $assessment,

                'result' => $result,
            ]
        );
    }
}
