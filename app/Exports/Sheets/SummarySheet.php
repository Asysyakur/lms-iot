<?php

namespace App\Exports\Sheets;

use App\Models\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SummarySheet implements
    FromCollection,
    WithHeadings
{
    public function collection()
    {
        return User::where(
            'role',
            'student'
        )
        ->get()
        ->map(function ($student) {

            return [

                'Nama' =>
                    $student->name,

                'Pretest' =>
                    $student->pretest_score,

                'Posttest' =>
                    $student->posttest_score,

                'Status' =>
                    $student->active
                        ? 'Aktif'
                        : 'Belum Login',
            ];
        });
    }

    public function headings(): array
    {
        return [

            'Nama',

            'Pretest',

            'Posttest',

            'Status',
        ];
    }
}