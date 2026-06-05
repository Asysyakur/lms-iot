<!-- resources/js/pages/student/meetings/quizzes/Start.vue -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
  ClipboardCheck,
  AlertTriangle,
  Trophy,
  RotateCcw,
  Clock3,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  meetingId: number;
}>();

const minimumScore = 80;
const attempts = 1;
const maxAttempts = 3;
const totalQuestions = 10;
const duration = 20;
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-blue-700 to-cyan-600 px-5 py-4 text-white shadow-sm">

      <div
        class="absolute right-0 top-0 h-28 w-28 rounded-full bg-white/10" />

      <div class="relative z-10">

        <!-- BADGE -->
        <span
          class="rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-semibold">

          Pertemuan {{ meetingId }}
        </span>

        <!-- TITLE -->
        <h1 class="mt-3 text-2xl font-bold">

          🧠 Kuis Pertemuan
        </h1>

        <!-- DESC -->
        <p class="mt-1 max-w-2xl text-sm text-blue-100">

          Kerjakan kuis untuk membuka
          tahap praktik mandiri.
        </p>
      </div>
    </section>

    <!-- WARNING -->
    <section
      class="rounded-xl border border-amber-200 bg-amber-50 p-4">

      <div class="flex items-start gap-3">

        <!-- ICON -->
        <div
          class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100">

          <AlertTriangle
            class="h-5 w-5 text-amber-600" />
        </div>

        <!-- CONTENT -->
        <div>

          <h2
            class="text-sm font-bold text-amber-700">

            Syarat Lanjut ke Praktik
          </h2>

          <p
            class="mt-1 text-sm leading-relaxed text-amber-700">

            Kamu harus memperoleh nilai
            minimal
            <span class="font-bold">
              {{ minimumScore }}
            </span>
            untuk membuka tahap praktik.
            Jika belum mencapai nilai minimum,
            kuis dapat diulang maksimal
            {{ maxAttempts }} kali.
          </p>
        </div>
      </div>
    </section>

    <!-- INFO -->
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

      <!-- TOTAL -->
      <div
        class="rounded-xl bg-white p-4 shadow-sm">

        <div
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">

          <ClipboardCheck
            class="h-5 w-5 text-blue-600" />
        </div>

        <h2
          class="mt-4 text-2xl font-bold text-slate-800">

          {{ totalQuestions }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Total Soal
        </p>
      </div>

      <!-- SCORE -->
      <div
        class="rounded-xl bg-white p-4 shadow-sm">

        <div
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">

          <Trophy
            class="h-5 w-5 text-emerald-600" />
        </div>

        <h2
          class="mt-4 text-2xl font-bold text-slate-800">

          {{ minimumScore }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Nilai Minimum
        </p>
      </div>

      <!-- ATTEMPT -->
      <div
        class="rounded-xl bg-white p-4 shadow-sm">

        <div
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">

          <RotateCcw
            class="h-5 w-5 text-purple-600" />
        </div>

        <h2
          class="mt-4 text-2xl font-bold text-slate-800">

          {{ attempts }}/{{ maxAttempts }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Percobaan
        </p>
      </div>

      <!-- DURATION -->
      <div
        class="rounded-xl bg-white p-4 shadow-sm">

        <div
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100">

          <Clock3
            class="h-5 w-5 text-orange-600" />
        </div>

        <h2
          class="mt-4 text-2xl font-bold text-slate-800">

          {{ duration }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Menit
        </p>
      </div>
    </div>

    <!-- ACTION -->
    <section
      class="rounded-xl bg-white p-4 shadow-sm">

      <div
        class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- LEFT -->
        <div>

          <h2
            class="text-base font-bold text-slate-800">

            Siap memulai kuis?
          </h2>

          <p
            class="mt-1 text-sm text-slate-500">

            Pastikan koneksi stabil sebelum
            memulai pengerjaan kuis.
          </p>
        </div>

        <!-- BUTTON -->
        <Link
          :href="`/student/meetings/${meetingId}/quiz/exam`"
          class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

          🚀 Mulai Kuis
        </Link>
      </div>
    </section>
  </div>
</template>