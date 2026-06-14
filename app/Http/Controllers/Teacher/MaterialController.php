<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $existingMaterial =
            Material::where(
                'meeting_id',
                $request->meeting_id
            )->first();

        $pdfPath =
            $existingMaterial?->pdf_file;

        if ($request->hasFile('pdf_file')) {

            $pdfPath = $request
                ->file('pdf_file')
                ->store(
                    'materials',
                    'public'
                );
        }

        $material =
            Material::updateOrCreate(
                [
                    'meeting_id' =>
                    $request->meeting_id,
                ],
                [
                    'title' =>
                    $request->title,

                    'description' =>
                    $request->description,

                    'video_url' =>
                    $request->video_url,

                    'trigger_question' =>
                    $request->trigger_question,

                    'reflection_question' =>
                    $request->reflection_question,

                    'pdf_file' =>
                    $pdfPath,
                ]
            );

        $material->pdf_url =
            $material->pdf_file
            ? asset(
                'storage/' .
                    $material->pdf_file
            )
            : null;

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
