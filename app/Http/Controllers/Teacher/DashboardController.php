<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Meeting;
use App\Models\AssessmentResult;

use Inertia\Inertia;

class DashboardController extends Controller
{
  public function index()
  {
    $teacher = auth()->user();

    /*
        |--------------------------------------------------------------------------
        | TOTAL SISWA
        |--------------------------------------------------------------------------
        */

    $totalStudents =
      User::where(
        'role',
        'student'
      )->count();

    /*
        |--------------------------------------------------------------------------
        | PRETEST
        |--------------------------------------------------------------------------
        */

    $pretestCompleted =
      AssessmentResult::where(
        'status',
        'submitted'
      )
      ->whereHas(
        'assessment',
        function ($query) {

          $query->where(
            'type',
            'pretest'
          );
        }
      )
      ->count();

    /*
        |--------------------------------------------------------------------------
        | PERTEMUAN AKTIF
        |--------------------------------------------------------------------------
        */

    $activeMeetings =
      Meeting::where(
        'is_active',
        true
      )->count();

    /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */

    $reports =
      Meeting::count();

    /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

    $stats = [

      [
        'title' =>
        'Total Siswa',

        'value' =>
        $totalStudents,

        'desc' =>
        'Siswa aktif',

        'icon' =>
        'Users',

        'color' =>
        'from-violet-500/10 to-purple-500/10 border-violet-200',

        'iconColor' =>
        'text-violet-500',
      ],

      [
        'title' =>
        'Pre-test Selesai',

        'value' =>
        $pretestCompleted,

        'desc' =>
        'Sudah mengerjakan',

        'icon' =>
        'ClipboardCheck',

        'color' =>
        'from-emerald-500/10 to-teal-500/10 border-emerald-200',

        'iconColor' =>
        'text-emerald-500',
      ],

      [
        'title' =>
        'Pertemuan Aktif',

        'value' =>
        $activeMeetings,

        'desc' =>
        'Aktif sekarang',

        'icon' =>
        'BookOpen',

        'color' =>
        'from-blue-500/10 to-cyan-500/10 border-blue-200',

        'iconColor' =>
        'text-blue-500',
      ],

      [
        'title' =>
        'Laporan',

        'value' =>
        $reports,

        'desc' =>
        'Data tersedia',

        'icon' =>
        'FileText',

        'color' =>
        'from-amber-500/10 to-orange-500/10 border-amber-200',

        'iconColor' =>
        'text-amber-500',
      ],
    ];

    /*
        |--------------------------------------------------------------------------
        | FEATURES
        |--------------------------------------------------------------------------
        */

    $features = [

      [
        'title' =>
        'Kelola Akun Siswa',

        'desc' =>
        'Tambah akun, edit, dan hapus akun siswa.',

        'icon' =>
        'UserCog',

        'color' =>
        'text-violet-500',

        'href' =>
        '/teacher/students',
      ],

      [
        'title' =>
        'Atur Pre-test',

        'desc' =>
        'Kelola soal pre-test dan durasi.',

        'icon' =>
        'ClipboardCheck',

        'color' =>
        'text-emerald-500',

        'href' =>
        '/teacher/assessments/pretest',
      ],

      [
        'title' =>
        'Manajemen Pertemuan',

        'desc' =>
        'Kelola materi, kuis, praktik, dan LKPD.',

        'icon' =>
        'BookOpen',

        'color' =>
        'text-blue-500',

        'href' =>
        '/teacher/meetings',
      ],

      [
        'title' =>
        'Atur Post-test',

        'desc' =>
        'Kelola soal post-test dan evaluasi akhir siswa.',

        'icon' =>
        'Settings2',

        'color' =>
        'text-amber-500',

        'href' =>
        '/teacher/assessments/posttest',
      ],

      [
        'title' =>
        'Monitoring & Laporan',

        'desc' =>
        'Lihat progress belajar dan aktivitas siswa.',

        'icon' =>
        'MonitorCheck',

        'color' =>
        'text-cyan-500',

        'href' =>
        '/teacher/reports',
      ],

    ];

    return Inertia::render(
      'teacher/dashboard/Index',
      [

        'teacher' => [
          'name' =>
          $teacher->name,
        ],

        'stats' =>
        $stats,

        'features' =>
        $features,
      ]
    );
  }
}
