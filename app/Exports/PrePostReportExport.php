<?php

namespace App\Exports;

use App\Exports\Sheets\AssessmentDetailSheet;
use App\Exports\Sheets\PrePostSummarySheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PrePostReportExport implements WithMultipleSheets
{
    public function __construct(
        protected ?string $class = null
    ) {}

    public function sheets(): array
    {
        return [

            new PrePostSummarySheet($this->class),

            new AssessmentDetailSheet(
                'pretest',
                'Detail Pre-test',
                $this->class
            ),

            new AssessmentDetailSheet(
                'posttest',
                'Detail Post-test',
                $this->class
            ),
        ];
    }
}
