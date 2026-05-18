<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function store(Request $request)
    {
        Evaluation::updateOrCreate(
            [
                'meeting_id' => $request->meeting_id,
            ],
            [
                'question' => $request->question,
            ]
        );
        $evaluation = Evaluation::where('meeting_id', $request->meeting_id)->first();
        return response()->json([
            'success' => true,
            'evaluation' => $evaluation,
        ]);
    }

    public function toggle(Evaluation $evaluation)
    {
        $evaluation->update([
            'is_active' =>
            !$evaluation->is_active,
        ]);

        return response()->json([
            'evaluation' => $evaluation,
        ]);
    }
}
