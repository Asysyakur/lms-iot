<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Practice;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function store(Request $request)
    {
        Practice::updateOrCreate(
            [
                'meeting_id' => $request->meeting_id,
            ],
            [
                'instruction' => $request->instruction,

                'makecode_url' => $request->makecode_url,
            ]
        );

        $practice = Practice::where('meeting_id', $request->meeting_id)->first();

        return response()->json([
            'success' => true,
            'practice' => $practice,
        ]);
    }

    public function toggle(Practice $practice)
    {
        $practice->update([
            'is_active' =>
            !$practice->is_active,
        ]);

        return response()->json([
            'practice' => $practice,
        ]);
    }
}
