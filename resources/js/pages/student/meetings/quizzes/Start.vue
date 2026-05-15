<!-- resources/js/pages/student/meetings/quizzes/Start.vue -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
  ClipboardCheck,
  AlertTriangle,
  Trophy,
  RotateCcw,
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
  <div class="space-y-6">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 to-cyan-600 p-6 text-white shadow-lg"
    >
      <div
        class="absolute right-0 top-0 h-40 w-40 rounded-full bg-white/10"
      />

      <div>
        <span
          class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold"
        >
          Pertemuan {{ meetingId }}
        </span>

        <h1 class="mt-4 text-3xl font-bold">
          🧠 Kuis Pertemuan
        </h1>

        <p class="mt-2 text-blue-100">
          Kerjakan kuis untuk membuka tahap
          praktik mandiri.
        </p>
      </div>
    </section>

    <!-- WARNING -->
    <section
      class="rounded-3xl border border-amber-200 bg-amber-50 p-5"
    >
      <div class="flex items-start gap-4">
        <div
          class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100"
        >
          <AlertTriangle
            class="h-6 w-6 text-amber-600"
          />
        </div>

        <div>
          <h2
            class="font-bold text-amber-700"
          >
            Syarat Lanjut ke Praktik
          </h2>

          <p
            class="mt-1 text-sm leading-relaxed text-amber-700"
          >
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
    <div class="grid gap-6 md:grid-cols-3">
      <div
        class="rounded-3xl bg-white p-6 shadow-sm"
      >
        <div
          class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100"
        >
          <ClipboardCheck
            class="h-6 w-6 text-blue-600"
          />
        </div>

        <h2
          class="mt-5 text-2xl font-bold text-slate-800"
        >
          {{ totalQuestions }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Total Soal
        </p>
      </div>

      <div
        class="rounded-3xl bg-white p-6 shadow-sm"
      >
        <div
          class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100"
        >
          <Trophy
            class="h-6 w-6 text-emerald-600"
          />
        </div>

        <h2
          class="mt-5 text-2xl font-bold text-slate-800"
        >
          {{ minimumScore }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Nilai Minimum
        </p>
      </div>

      <div
        class="rounded-3xl bg-white p-6 shadow-sm"
      >
        <div
          class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100"
        >
          <RotateCcw
            class="h-6 w-6 text-purple-600"
          />
        </div>

        <h2
          class="mt-5 text-2xl font-bold text-slate-800"
        >
          {{ attempts }}/{{ maxAttempts }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Percobaan Kuis
        </p>
      </div>
    </div>

    <!-- ACTION -->
    <section
      class="rounded-3xl bg-white p-6 shadow-sm"
    >
      <div
        class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
      >
        <div>
          <h2
            class="text-lg font-bold text-slate-800"
          >
            Siap memulai kuis?
          </h2>

          <p
            class="mt-1 text-sm text-slate-500"
          >
            Durasi pengerjaan:
            {{ duration }} menit.
          </p>
        </div>

        <Link
          :href="`/student/meetings/${meetingId}/quiz/exam`"
          class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-8 py-4 font-semibold text-white transition hover:bg-blue-700"
        >
          🚀 Mulai Kuis
        </Link>
      </div>
    </section>
  </div>
</template>