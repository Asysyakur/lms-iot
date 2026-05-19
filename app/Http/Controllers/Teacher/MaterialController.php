<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $pdfPath = null;

        if ($request->hasFile('pdf_file')) {

            $pdfPath = $request
                ->file('pdf_file')
                ->store(
                    'materials',
                    'public'
                );
        }

        $material = Material::updateOrCreate(
            [
                'meeting_id' => $request->meeting_id,
            ],
            [
                'title' => $request->title,

                'description' => $request->description,

                'video_url' => $request->video_url,

                'trigger_question' =>
                $request->trigger_question,

                'reflection_question' =>
                $request->reflection_question,

                'pdf_file' => $pdfPath,
            ]
        );

        return response()->json([
            'success' => true,

            'material' => $material,
        ]);
    }

    public function toggle(Material $material)
    {
        $material->is_active =
            !$material->is_active;

        $material->save();

        return response()->json([
            'success' => true,
            'material' => $material,
        ]);
    }
}
