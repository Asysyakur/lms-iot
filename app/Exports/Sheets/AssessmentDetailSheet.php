<?php

namespace App\Exports\Sheets;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentDetailSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithStrictNullComparison, WithStyles, WithTitle
{
    protected ?Assessment $assessment;

    protected Collection $questions;

    public function __construct(
        protected string $type,
        protected string $sheetTitle
    ) {

        $this->assessment =
            Assessment::where('type', $this->type)
                ->orderByRaw('CASE WHEN target_class IS NULL THEN 0 ELSE 1 END')
                ->first();

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
            ->get()
            ->map(function ($student) {

                $result =
                    AssessmentResult::with('answers')
                        ->where(
                            'student_id',
                            $student->id
                        )
                        ->whereHas(
                            'assessment',
                            fn($query) =>
                            $query->where(
                                'type',
                                $this->type
                            )->forStudentClass($student->class)
                        )
                        ->latest()
                        ->first();

                $answers =
                    $result
                    ? $result->answers->keyBy('question_id')
                    : collect();

                $row = [

                    'Nama Siswa' => $student->name,
                    'Kelas' => $student->class ?? '-',
                ];

                foreach ($this->questions as $index => $question) {

                    $answer =
                        $answers->get($question->id);

                    $row['No '.($index + 1)] =
                        $answer
                        ? ($answer->is_correct ? 1 : 0)
                        : '-';
                }

                return $row;
            });
    }

    public function headings(): array
    {
        $headings = [
            'Nama Siswa',
            'Kelas',
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
