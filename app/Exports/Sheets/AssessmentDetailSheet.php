<?php

namespace App\Exports\Sheets;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentDetailSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    protected ?Assessment $assessment;

    protected Collection $questions;

    public function __construct(
        protected string $type,
        protected string $sheetTitle
    ) {

        $this->assessment =
            Assessment::where(
                'type',
                $this->type
            )->first();

        $this->questions =
            $this->assessment
            ? $this->assessment->questions()
                ->orderBy('id')
                ->get()
            : collect();
    }

    public function collection()
    {
        if (! $this->assessment) {

            return collect();
        }

        return User::where(
            'role',
            'student'
        )
            ->with([
                'assessmentResults' => function ($query) {

                    $query->where(
                        'assessment_id',
                        $this->assessment->id
                    )->with('answers');
                },
            ])
            ->get()
            ->map(function ($student) {

                $result =
                    $student->assessmentResults
                        ->first();

                $answers =
                    $result
                    ? $result->answers->keyBy('question_id')
                    : collect();

                $row = [

                    'Nama Siswa' => $student->name,
                ];

                foreach ($this->questions as $index => $question) {

                    $answer =
                        $answers->get($question->id);

                    $row['No '.($index + 1)] =
                        $answer
                        ? ($answer->is_correct ? 'Benar' : 'Salah')
                        : '-';
                }

                return $row;
            });
    }

    public function headings(): array
    {
        $headings = [
            'Nama Siswa',
        ];

        foreach ($this->questions as $index => $question) {

            $headings[] = 'No '.($index + 1);
        }

        return $headings;
    }

    public function title(): string
    {
        return $this->sheetTitle;
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
