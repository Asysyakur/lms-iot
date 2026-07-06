<?php

namespace App\Exports;

use App\Exports\Sheets\AssessmentDetailSheet;
use App\Exports\Sheets\PrePostSummarySheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PrePostReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [

            new PrePostSummarySheet,

            new AssessmentDetailSheet(
                'pretest',
                'Detail Pre-test'
            ),

            new AssessmentDetailSheet(
                'posttest',
                'Detail Post-test'
            ),
        ];
    }
}
