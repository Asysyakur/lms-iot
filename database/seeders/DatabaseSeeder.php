<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
    }
}