<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =========================================================
        // USERS
        // =========================================================

        // ADMIN
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@iot.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // GURU
        User::create([
            'name' => 'Pak Budi',
            'username' => 'pakbudi',
            'email' => 'guru@iot.com',
            'role' => 'teacher',
            'password' => Hash::make('password'),
        ]);

        // SISWA 1
        User::create([
            'name' => 'Andi Pratama',
            'username' => 'andi01',
            'email' => null,
            'role' => 'student',
            'password' => Hash::make('password'),
        ]);

        // SISWA 2
        User::create([
            'name' => 'Siti Rahma',
            'username' => 'siti01',
            'email' => null,
            'role' => 'student',
            'password' => Hash::make('password'),
        ]);

        // =========================================================
        // PRETEST
        // =========================================================

        $pretest = Assessment::create([
            'title' => 'Pre-test IoT',
            'type' => 'pretest',
            'open_date' => now()->toDateString(),
            'open_time' => '08:00',
            'duration' => 30,
            'attempts' => 1,
        ]);

        // PRETEST QUESTIONS

        Question::create([
            'assessment_id' => $pretest->id,

            'question' => 'Apa fungsi utama sensor dalam sistem IoT?',

            'type' => 'multiple_choice',

            'option_a' => 'Mengambil data dari lingkungan',
            'option_b' => 'Menyimpan data',
            'option_c' => 'Menghapus data',
            'option_d' => 'Menampilkan video',

            'answer' => 'A',

            'is_active' => true,
        ]);

        Question::create([
            'assessment_id' => $pretest->id,

            'question' => 'Perangkat utama pada Micro:bit adalah?',

            'type' => 'multiple_choice',

            'option_a' => 'Arduino',
            'option_b' => 'CPU',
            'option_c' => 'Mikrokontroler',
            'option_d' => 'Sensor Kamera',

            'answer' => 'C',

            'is_active' => true,
        ]);

        // =========================================================
        // POSTTEST
        // =========================================================

        $posttest = Assessment::create([
            'title' => 'Post-test IoT',
            'type' => 'posttest',
            'open_date' => now()->toDateString(),
            'open_time' => '10:00',
            'duration' => 45,
            'attempts' => 1,
        ]);

        // POSTTEST QUESTIONS

        Question::create([
            'assessment_id' => $posttest->id,

            'question' => 'Jelaskan alur kerja IoT secara singkat.',

            'type' => 'essay',

            'answer' => 'Rubrik',

            'is_active' => true,
        ]);

        Question::create([
            'assessment_id' => $posttest->id,

            'question' => 'Apa fungsi internet pada sistem IoT?',

            'type' => 'multiple_choice',

            'option_a' => 'Menghubungkan perangkat',
            'option_b' => 'Menghapus data',
            'option_c' => 'Mematikan sensor',
            'option_d' => 'Mengurangi listrik',

            'answer' => 'A',

            'is_active' => true,
        ]);
    }
}