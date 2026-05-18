<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{

  public const STATUS_IN_PROGRESS = 'in_progress';
  public const STATUS_SUBMITTED = 'submitted';
  public const STATUS_TIMEOUT = 'timeout';
  
  protected $fillable = [
    'assessment_id',
    'student_id',
    'score',
    'correct_answers',
    'wrong_answers',
    'status',
    'started_at',
    'submitted_at',
  ];

  protected $casts = [
    'started_at' => 'datetime',
    'submitted_at' => 'datetime',
  ];

  /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

  public function assessment()
  {
    return $this->belongsTo(Assessment::class);
  }

  public function student()
  {
    return $this->belongsTo(User::class, 'student_id');
  }

  public function answers()
  {
    return $this->hasMany(AssessmentAnswer::class);
  }
}
