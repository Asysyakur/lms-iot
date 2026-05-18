<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'meeting_id',
        'question',
        'is_active',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
