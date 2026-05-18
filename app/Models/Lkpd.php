<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lkpd extends Model
{
    protected $fillable = [
        'meeting_id',
        'template_file',
        'google_docs_url',
        'submission_note',
        'is_active',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
