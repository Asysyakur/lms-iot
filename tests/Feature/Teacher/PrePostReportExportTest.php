<?php

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

test('teacher can export pre/post test report with per-question answer detail', function () {
    Excel::fake();

    $teacher = User::factory()->create([
        'username' => 'teacher1',
        'role' => 'teacher',
    ]);

    $student = User::factory()->create([
        'username' => 'student1',
        'role' => 'student',
    ]);

    $pretest = Assessment::create([
        'title' => 'Pre-test',
        'type' => 'pretest',
        'duration' => 30,
        'attempts' => 1,
    ]);

    $questionOne = Question::create([
        'assessment_id' => $pretest->id,
        'question' => 'Soal 1',
        'type' => 'multiple_choice',
        'answer' => 'A',
    ]);

    $questionTwo = Question::create([
        'assessment_id' => $pretest->id,
        'question' => 'Soal 2',
        'type' => 'multiple_choice',
        'answer' => 'B',
    ]);

    $result = AssessmentResult::create([
        'assessment_id' => $pretest->id,
        'student_id' => $student->id,
        'score' => 50,
        'correct_answers' => 1,
        'wrong_answers' => 1,
        'status' => 'submitted',
    ]);

    AssessmentAnswer::create([
        'assessment_result_id' => $result->id,
        'question_id' => $questionOne->id,
        'answer' => 'A',
        'is_correct' => true,
    ]);

    AssessmentAnswer::create([
        'assessment_result_id' => $result->id,
        'question_id' => $questionTwo->id,
        'answer' => 'A',
        'is_correct' => false,
    ]);

    $response = $this->actingAs($teacher)
        ->get('/teacher/reports/assessments/export');

    $response->assertOk();

    Excel::assertDownloaded(
        'laporan-pretest-posttest.xlsx',
        function ($export) use ($student) {

            $sheets = $export->sheets();

            $detailSheet = collect($sheets)->first(
                fn ($sheet) => $sheet->title() === 'Detail Pre-test'
            );

            expect($detailSheet->headings())->toBe([
                'Nama Siswa',
                'No 1',
                'No 2',
            ]);

            $row = $detailSheet->collection()->firstWhere(
                'Nama Siswa',
                $student->name
            );

            expect($row['No 1'])->toBe('Benar');
            expect($row['No 2'])->toBe('Salah');

            return true;
        }
    );
});
