<?php

use App\Models\Meeting;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('teacher meeting report displays the highest quiz score across attempts', function () {
    $teacher = User::factory()->create([
        'role' => 'teacher',
    ]);

    $student = User::factory()->create([
        'role' => 'student',
    ]);

    $meeting = Meeting::create([
        'title' => 'Pertemuan 1',
        'meeting_number' => 1,
    ]);

    QuizAttempt::create([
        'user_id' => $student->id,
        'meeting_id' => $meeting->id,
        'attempt_number' => 1,
        'score' => 60,
        'correct_answers' => 3,
        'total_questions' => 5,
    ]);

    QuizAttempt::create([
        'user_id' => $student->id,
        'meeting_id' => $meeting->id,
        'attempt_number' => 2,
        'score' => 100,
        'correct_answers' => 5,
        'total_questions' => 5,
        'passed' => true,
    ]);

    $this->actingAs($teacher)
        ->get("/teacher/reports/meetings/{$meeting->id}")
        ->assertInertia(fn (Assert $page) => $page
            ->component('teacher/reports/MeetingDetail')
            ->where('students.0.id', $student->id)
            ->where('students.0.quiz', 100),
        );
});
