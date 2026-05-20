<?php

namespace App\Exports\Sheets;

use App\Models\Meeting;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MeetingSheet implements
    FromCollection,
    WithHeadings
{
    protected $meeting;

    public function __construct(
        Meeting $meeting
    ) {

        $this->meeting =
            $meeting;
    }

    public function collection()
    {
        return collect(
            $this->meeting->students()
        )->map(function ($student) {

            return [

                'Nama' =>
                $student->name,

                'Pemantik' =>
                $student->triggerScore,

                'Quiz' =>
                $student->quiz,

                'Praktik' =>
                $student->practiceScore,

                'Evaluasi' =>
                $student->evaluationScore,

                'Durasi' =>
                gmdate(
                    'H:i:s',
                    $student->duration_seconds ?? 0
                ),
            ];
        });
    }
    
    public function headings(): array
    {
        return [

            'Nama',

            'Pemantik',

            'Quiz',

            'Praktik',

            'Evaluasi',

            'Durasi Akses',
        ];
    }
}
