<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lkpd;
use Illuminate\Http\Request;

class LkpdController extends Controller
{
    public function store(Request $request)
    {
        $file = null;

        if ($request->hasFile('template_file')) {
            $file = $request
                ->file('template_file')
                ->store('lkpd', 'public');
        }

        Lkpd::updateOrCreate(
            [
                'meeting_id' => $request->meeting_id,
            ],
            [
                'template_file' => $file,

                'google_docs_url' =>
                $request->google_docs_url,

                'submission_note' =>
                $request->submission_note,
            ]
        );

        $lkpd = Lkpd::where('meeting_id', $request->meeting_id)->first();

        return response()->json([
            'success' => true,
            'lkpd' => $lkpd,
        ]);
    }

    public function toggle(Lkpd $lkpd)
    {
        $lkpd->update([
            'is_active' =>
            !$lkpd->is_active,
        ]);

        return response()->json([
            'lkpd' => $lkpd,
        ]);
    }
}
