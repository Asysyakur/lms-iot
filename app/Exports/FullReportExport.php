<?php

namespace App\Exports;

use App\Models\Meeting;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\MeetingSheet;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FullReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        /**
         * SUMMARY
         */
        $sheets[] = new SummarySheet();

        /**
         * MEETING SHEETS
         */
        foreach (Meeting::all() as $meeting) {

            $sheets[] =
                new MeetingSheet($meeting);
        }

        return $sheets;
    }
}