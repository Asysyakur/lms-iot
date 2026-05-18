<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    protected $fillable = [
        'meeting_id',
        'instruction',
        'makecode_url',
        'is_active',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
