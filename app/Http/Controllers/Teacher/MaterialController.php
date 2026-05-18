<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $material = Material::updateOrCreate(
            [
                'meeting_id' => $request->meeting_id,
            ],
            [
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
                'trigger_question' => $request->trigger_question,
                'reflection_question' => $request->reflection_question,
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
