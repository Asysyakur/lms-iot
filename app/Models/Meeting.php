<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'meeting_number',
        'title',
        'description',
        'status',
        'is_active',
    ];

    public function material()
    {
        return $this->hasOne(Material::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function practice()
    {
        return $this->hasOne(Practice::class);
    }

    public function lkpd()
    {
        return $this->hasOne(Lkpd::class);
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }
}
