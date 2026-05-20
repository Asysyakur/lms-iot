<?php

namespace App\Exports;

use App\Models\Meeting;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

class MeetingDetailExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
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

                'Nama Siswa' =>
                $student->name,

                'Jawaban Pemantik' =>
                $student->triggerAnswer ?? '-',

                'Nilai Pemantik' =>
                $student->triggerScore ?? 0,

                'Waktu Akses' =>
                gmdate(
                    'H:i:s',
                    $student->duration_seconds ?? 0
                ),

                'Jawaban Refleksi' =>
                is_array($student->reflectionAnswer)
                    ? implode(
                        ', ',
                        $student->reflectionAnswer
                    )
                    : ($student->reflectionAnswer ?? '-'),

                'Nilai Quiz' =>
                $student->quiz ?? 0,

                'Link Praktik' =>
                $student->practice ?? '-',

                'Nilai Praktik' =>
                $student->practiceScore ?? 0,

                'LKPD' =>
                $student->lkpd ?? '-',

                'Jawaban Evaluasi' =>
                is_array($student->evaluation)
                    ? implode(
                        ', ',
                        $student->evaluation
                    )
                    : ($student->evaluation ?? '-'),

                'Nilai Evaluasi' =>
                $student->evaluationScore ?? 0,

                'Total Nilai' =>
                round(
                    (
                        ($student->triggerScore ?? 0)
                        +
                        ($student->quiz ?? 0)
                        +
                        ($student->practiceScore ?? 0)
                        +
                        ($student->evaluationScore ?? 0)
                    ) / 4
                ),
            ];
        });
    }

    public function headings(): array
    {
        return [

            'Nama Siswa',

            'Jawaban Pemantik',

            'Nilai Pemantik',

            'Waktu Akses',

            'Jawaban Refleksi',

            'Nilai Quiz',

            'Link Praktik',

            'Nilai Praktik',

            'LKPD',

            'Jawaban Evaluasi',

            'Nilai Evaluasi',

            'Total Nilai',
        ];
    }

    public function styles(
        Worksheet $sheet
    ) {

        return [

            1 => [

                'font' => [

                    'bold' => true,

                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],

                'fill' => [

                    'fillType' => 'solid',

                    'startColor' => [
                        'rgb' => '173B74',
                    ],
                ],
            ],
        ];
    }
}
