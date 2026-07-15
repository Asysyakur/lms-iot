<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'title',
        'description',

        'type',
        'target_class',

        'open_date',
        'open_time',

        'duration',
        'attempts',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function isAccessibleTo(?string $class): bool
    {
        return $this->target_class === null
            || $this->target_class === $class;
    }

    public function scopeForStudentClass($query, ?string $class)
    {
        return $query
            ->where(function ($query) use ($class) {
                $query->whereNull('target_class');

                if ($class !== null) {
                    $query->orWhere('target_class', $class);
                }
            })
            ->orderByRaw(
                $class === null
                    ? 'CASE WHEN target_class IS NULL THEN 0 ELSE 1 END'
                    : 'CASE WHEN target_class = ? THEN 0 WHEN target_class IS NULL THEN 1 ELSE 2 END',
                $class === null ? [] : [$class]
            );
    }

    public static function defaultTitle(string $type, ?string $class = null): string
    {
        $base = $type === 'pretest'
            ? 'Pre-test IoT'
            : 'Post-test IoT';

        return $class ? $base.' - '.$class : $base;
    }
}
