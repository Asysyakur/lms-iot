<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrePostSummarySheet implements FromCollection, ShouldAutoSize, WithHeadings, WithStrictNullComparison, WithStyles, WithTitle
{
    public function collection()
    {
        return User::with(
            'assessmentResults.assessment'
        )
            ->where(
                'role',
                'student'
            )
            ->get()
            ->map(function ($student) {

                $pretest =
                    $student->assessmentResults
                        ->first(function ($result) {

                            return
                                $result->assessment?->type
                                === 'pretest';
                        });

                $posttest =
                    $student->assessmentResults
                        ->first(function ($result) {

                            return
                                $result->assessment?->type
                                === 'posttest';
                        });

                return [

                    'Nama Siswa' => $student->name,

                    'Nilai Pre-test' => $pretest?->score ?? '-',

                    'Nilai Post-test' => $posttest?->score ?? '-',

                    'Status Pre-test' => $pretest ? 'Selesai' : 'Belum',

                    'Status Post-test' => $posttest ? 'Selesai' : 'Belum',
                ];
            });
    }

    public function headings(): array
    {
        return [

            'Nama Siswa',

            'Nilai Pre-test',

            'Nilai Post-test',

            'Status Pre-test',

            'Status Post-test',
        ];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function styles(
        Worksheet $sheet
    ) {

        return [

            /**
             * HEADER
             */
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
