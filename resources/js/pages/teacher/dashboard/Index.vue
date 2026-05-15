<!-- resources/js/pages/teacher/dashboard/Index.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import {
  Users,
  ClipboardCheck,
  BookOpen,
  FileText,
  UserCog,
  Settings2,
  MonitorCheck,
  BarChart3,
} from 'lucide-vue-next';

defineOptions({
  layout: TeacherSidebarLayout,
});

const stats = [
  {
    title: 'Total Siswa',
    value: 32,
    desc: 'Kelas XI TKJ-T-1',
    icon: Users,
    color:
      'from-violet-500/10 to-purple-500/10 border-violet-200',
    iconColor: 'text-violet-500',
  },

  {
    title: 'Pre-test Selesai',
    value: 28,
    desc: '87% siswa',
    icon: ClipboardCheck,
    color:
      'from-emerald-500/10 to-teal-500/10 border-emerald-200',
    iconColor: 'text-emerald-500',
  },

  {
    title: 'Pertemuan Aktif',
    value: 3,
    desc: 'aktif sekarang',
    icon: BookOpen,
    color:
      'from-blue-500/10 to-cyan-500/10 border-blue-200',
    iconColor: 'text-blue-500',
  },

  {
    title: 'Laporan',
    value: 5,
    desc: 'siap diakses',
    icon: FileText,
    color:
      'from-amber-500/10 to-orange-500/10 border-amber-200',
    iconColor: 'text-amber-500',
  },
];

const features = [
  {
    title: 'Kelola Akun Siswa',
    desc: 'Tambah akun, buat username/password, dan hapus akun siswa.',
    icon: UserCog,
    color: 'text-violet-500',
  },

  {
    title: 'Atur Pre-test',
    desc: 'Kelola soal pre-test, durasi, dan ketentuan pengerjaan.',
    icon: ClipboardCheck,
    color: 'text-emerald-500',
  },

  {
    title: 'Manajemen Pertemuan',
    desc: 'Tambah materi, kuis, praktik, serta template LKPD tiap pertemuan.',
    icon: BookOpen,
    color: 'text-blue-500',
  },

  {
    title: 'Atur Post-test',
    desc: 'Kelola soal post-test dan evaluasi akhir siswa.',
    icon: Settings2,
    color: 'text-amber-500',
  },

  {
    title: 'Monitoring & Laporan',
    desc: 'Lihat progress belajar dan pengerjaan aktivitas per pertemuan.',
    icon: MonitorCheck,
    color: 'text-cyan-500',
  },

  {
    title: 'Pengaturan',
    desc: 'Kelola kelas dan pengaturan LMS.',
    icon: BarChart3,
    color: 'text-rose-500',
  },
];
</script>

<template>
  <div class="space-y-8">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#173B74] via-[#114084] to-emerald-500 p-8 text-white shadow-xl"
    >
      <div
        class="absolute right-0 top-0 h-56 w-56 rounded-full bg-white/10"
      />

      <div
        class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
      >
        <div>
          <div
            class="mb-4 flex items-center gap-3"
          >
            <span
              class="rounded-full bg-white/20 px-4 py-1 text-xs font-bold uppercase tracking-wider"
            >
              Dashboard Guru
            </span>

            <span
              class="rounded-full bg-emerald-400/20 px-4 py-1 text-xs font-bold text-emerald-100"
            >
              Aktif Hari Ini
            </span>
          </div>

          <h1
            class="text-4xl font-black"
          >
            Halo, Pak Budi! 👋
          </h1>

          <p
            class="mt-3 max-w-3xl text-lg text-slate-200"
          >
            Kelola pembelajaran IoT
            Micro:bit siswa, pre-test,
            pertemuan, post-test,
            monitoring, dan laporan
            aktivitas.
          </p>
        </div>

        <button
          class="rounded-2xl bg-white/20 px-6 py-4 font-bold text-white backdrop-blur transition hover:bg-white/30"
        >
          + Tambah Pertemuan
        </button>
      </div>
    </section>

    <!-- STATS -->
    <section
      class="grid gap-6 md:grid-cols-2 xl:grid-cols-4"
    >
      <div
        v-for="stat in stats"
        :key="stat.title"
        class="rounded-3xl border bg-gradient-to-br p-6 shadow-sm"
        :class="stat.color"
      >
        <div
          class="flex items-start justify-between"
        >
          <div>
            <p
              class="text-sm font-semibold text-slate-500"
            >
              {{ stat.title }}
            </p>

            <h2
              class="mt-3 text-5xl font-black text-slate-800"
            >
              {{ stat.value }}
            </h2>

            <p
              class="mt-2 text-sm text-slate-500"
            >
              {{ stat.desc }}
            </p>
          </div>

          <div
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm"
          >
            <component
              :is="stat.icon"
              class="h-7 w-7"
              :class="stat.iconColor"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section
      class="grid gap-6 md:grid-cols-2 xl:grid-cols-3"
    >
      <div
        v-for="feature in features"
        :key="feature.title"
        class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
      >
        <div
          class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100"
        >
          <component
            :is="feature.icon"
            class="h-7 w-7"
            :class="feature.color"
          />
        </div>

        <h2
          class="mt-5 text-xl font-bold text-slate-800"
        >
          {{ feature.title }}
        </h2>

        <p
          class="mt-3 text-sm leading-relaxed text-slate-500"
        >
          {{ feature.desc }}
        </p>

        <button
          class="mt-6 rounded-2xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200"
        >
          Buka Menu →
        </button>
      </div>
    </section>
  </div>
</template>