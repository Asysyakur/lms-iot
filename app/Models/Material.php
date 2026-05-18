<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'meeting_id',
        'title',
        'description',
        'pdf_file',
        'video_url',
        'trigger_question',
        'reflection_question',
        'is_active',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
