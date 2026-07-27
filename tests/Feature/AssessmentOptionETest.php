<?php

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('teacher can save option E for an assessment question', function () {
    $teacher = User::factory()->create([
        'role' => 'teacher',
    ]);

    $this->actingAs($teacher)
        ->post('/teacher/questions', [
            'type' => 'pretest',
            'question_type' => 'multiple_choice',
            'question' => 'Pilih opsi E',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'answer' => 'E',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('questions', [
        'question' => 'Pilih opsi E',
        'option_e' => 'E',
        'answer' => 'E',
    ]);
});

test('option E is retained and can be submitted by a student', function () {
    $student = User::factory()->create([
        'role' => 'student',
    ]);

    $assessment = Assessment::create([
        'title' => 'Pre-test',
        'type' => 'pretest',
        'duration' => 30,
        'attempts' => 1,
    ]);

    $question = Question::create([
        'assessment_id' => $assessment->id,
        'question' => 'Pilih opsi E',
        'type' => 'multiple_choice',
        'option_a' => 'A',
        'option_b' => 'B',
        'option_c' => 'C',
        'option_d' => 'D',
        'option_e' => 'E',
        'answer' => 'E',
    ]);

    $result = AssessmentResult::create([
        'assessment_id' => $assessment->id,
        'student_id' => $student->id,
        'status' => 'in_progress',
    ]);

    $this->actingAs($student)
        ->postJson('/student/assessments/answer', [
            'assessment_result_id' => $result->id,
            'question_id' => $question->id,
            'answer' => 'E',
        ])
        ->assertOk();

    expect($question->fresh()->option_e)->toBe('E');

    $this->assertDatabaseHas('assessment_answers', [
        'assessment_result_id' => $result->id,
        'question_id' => $question->id,
        'answer' => 'E',
        'is_correct' => true,
    ]);
});
