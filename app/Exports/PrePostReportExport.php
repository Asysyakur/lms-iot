<?php

namespace App\Exports;

use App\Models\User;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

class PrePostReportExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    public function collection()
    {
        return User::with(
            'assessmentResults'
        )
            ->where(
                'role',
                'student'
            )
            ->get()
            ->map(function ($student) {

                $pretest =
                    $student
                    ->assessmentResults
                    ->where(
                        'assessment_id',
                        1
                    )
                    ->first();

                $posttest =
                    $student
                    ->assessmentResults
                    ->where(
                        'assessment_id',
                        2
                    )
                    ->first();

                return [

                    'Nama Siswa' =>
                    $student->name,

                    'Nilai Pre-test' =>
                    $pretest?->score ?? '-',

                    'Nilai Post-test' =>
                    $posttest?->score ?? '-',

                    'Status Pre-test' =>
                    $pretest?->status
                        ?? 'Belum',

                    'Status Post-test' =>
                    $posttest?->status
                        ?? 'Belum',
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
                        'rgb' => 'FFFFFF'
                    ],
                ],

                'fill' => [

                    'fillType' => 'solid',

                    'startColor' => [
                        'rgb' => '173B74'
                    ],
                ],
            ],
        ];
    }
}
