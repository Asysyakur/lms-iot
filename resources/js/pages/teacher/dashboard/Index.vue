<!-- resources/js/pages/teacher/dashboard/Index.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

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

const props = defineProps<{
  teacher: any;
  stats: any[];
  features: any[];
}>();

const iconMap: any = {
  Users,
  ClipboardCheck,
  BookOpen,
  FileText,
  UserCog,
  Settings2,
  MonitorCheck,
  BarChart3,
};
</script>

<template>
  <div class="space-y-8">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#173B74] via-[#114084] to-emerald-500 p-8 text-white shadow-xl">
      <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-white/10" />

      <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <div class="mb-4 flex items-center gap-3">
            <span class="rounded-full bg-white/20 px-4 py-1 text-xs font-bold uppercase tracking-wider">
              Dashboard Guru
            </span>

            <span class="rounded-full bg-emerald-400/20 px-4 py-1 text-xs font-bold text-emerald-100">
              Aktif Hari Ini
            </span>
          </div>

          <h1 class="text-4xl font-black">
            Halo, {{ props.teacher.name || 'Guru' }}! 👋
          </h1>

          <p class="mt-3 max-w-3xl text-lg text-slate-200">
            Kelola pembelajaran IoT
            Micro:bit siswa, pre-test,
            pertemuan, post-test,
            monitoring, dan laporan
            aktivitas.
          </p>
        </div>

        <button
          class="rounded-2xl bg-white/20 px-6 py-4 font-bold text-white backdrop-blur transition hover:bg-white/30">
          + Tambah Pertemuan
        </button>
      </div>
    </section>

    <!-- STATS -->
    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
      <div v-for="stat in stats" :key="stat.title" class="rounded-3xl border bg-gradient-to-br p-6 shadow-sm"
        :class="stat.color">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-semibold text-slate-500">
              {{ stat.title }}
            </p>

            <h2 class="mt-3 text-5xl font-black text-slate-800">
              {{ stat.value }}
            </h2>

            <p class="mt-2 text-sm text-slate-500">
              {{ stat.desc }}
            </p>
          </div>

          <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
            <component :is="iconMap[stat.icon]" class="h-7 w-7" :class="stat.iconColor" />
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
      <div v-for="feature in features" :key="feature.title"
        class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
          <component :is="iconMap[feature.icon]" class="h-7 w-7" :class="feature.color" />
        </div>

        <h2 class="mt-5 text-xl font-bold text-slate-800">
          {{ feature.title }}
        </h2>

        <p class="mt-3 text-sm leading-relaxed text-slate-500">
          {{ feature.desc }}
        </p>

        <Link :href="feature.href"
          class="mt-6 inline-block rounded-2xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
        Buka Menu →
        </Link>
      </div>
    </section>
  </div>
</template>