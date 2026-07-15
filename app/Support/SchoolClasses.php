<?php

namespace App\Support;

final class SchoolClasses
{
    public const OPTIONS = [
        'X TKJ-T-1',
        'X TKJ-T-2',
        'XI TKJ-T-1',
        'XI TKJ-T-2',
        'XII TKJ-T-1',
        'XII TKJ-T-2',
    ];

    public static function options(): array
    {
        return self::OPTIONS;
    }
}
