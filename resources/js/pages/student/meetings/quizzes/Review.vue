<!-- resources/js/pages/student/meetings/quizzes/Review.vue -->

<script setup lang="ts">
import { computed } from 'vue';

import { Link } from '@inertiajs/vue3';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

defineOptions({
  layout: StudentSidebarLayout,
});

const score = 90;
const correctAnswers = 9;
const wrongAnswers = 1;
const minimumScore = 80;
const attempts = 1;

const passed = computed(() => {
  return score >= minimumScore;
});

const canRetry = computed(() => {
  return score < 80 && attempts < 3;
});

const canContinue = computed(() => {
  return score >= 80 || attempts >= 3;
});

const reviews = [
  {
    question:
      'Sensor cahaya pada Microbit dapat membaca...',
    student: 'A. LED Matrix',
    correct: 'B. Intensitas cahaya',
    isCorrect: false,
  },

  {
    question:
      'Tombol A dan B pada Microbit termasuk...',
    student: 'A. Input',
    correct: 'A. Input',
    isCorrect: true,
  },
];
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

      <div
        class="flex items-start justify-between"
      >
        <div>
          <span
            class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold"
          >
            Hasil Kuis
          </span>

          <h1
            class="mt-4 text-3xl font-bold"
          >
            🎉 Hasil Kuis Pertemuan
          </h1>

          <p class="mt-2 text-blue-100">
            Berikut hasil pengerjaan kuis
            kamu.
          </p>
        </div>

        <div
          class="rounded-3xl bg-white/10 p-6 text-center backdrop-blur"
        >
          <p class="text-sm text-blue-100">
            Nilai
          </p>

          <h2
            class="mt-2 text-5xl font-bold"
          >
            {{ score }}
          </h2>
        </div>
      </div>
    </section>

    <!-- STATS -->
    <div class="grid gap-6 md:grid-cols-4">
      <div
        class="rounded-3xl bg-white p-6 shadow-sm"
      >
        <h2
          class="text-3xl font-bold text-emerald-500"
        >
          {{ correctAnswers }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Jawaban Benar
        </p>
      </div>

      <div
        class="rounded-3xl bg-white p-6 shadow-sm"
      >
        <h2
          class="text-3xl font-bold text-red-500"
        >
          {{ wrongAnswers }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Jawaban Salah
        </p>
      </div>

      <div
        class="rounded-3xl bg-white p-6 shadow-sm"
      >
        <h2
          class="text-3xl font-bold text-blue-500"
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
        <h2
          class="text-3xl font-bold text-purple-500"
        >
          {{ attempts }}/3
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Percobaan
        </p>
      </div>
    </div>

    <!-- STATUS -->
    <section
      :class="
        passed
          ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
          : 'border-red-200 bg-red-50 text-red-700'
      "
      class="rounded-3xl border p-5"
    >
      <h2 class="font-bold">
        {{
          passed
            ? '✅ Selamat! Kamu lulus kuis.'
            : '❌ Nilai belum memenuhi syarat.'
        }}
      </h2>
    </section>

    <!-- REVIEW -->
    <section class="space-y-5">
      <div
        v-for="(review, index) in reviews"
        :key="index"
        :class="
          review.isCorrect
            ? 'border-emerald-200'
            : 'border-red-200'
        "
        class="rounded-3xl border bg-white p-6 shadow-sm"
      >
        <div
          class="flex items-center justify-between"
        >
          <h2
            class="font-bold text-slate-800"
          >
            Soal {{ index + 1 }}
          </h2>

          <span
            :class="
              review.isCorrect
                ? 'bg-emerald-100 text-emerald-700'
                : 'bg-red-100 text-red-700'
            "
            class="rounded-full px-3 py-1 text-xs font-semibold"
          >
            {{
              review.isCorrect
                ? 'Benar'
                : 'Salah'
            }}
          </span>
        </div>

        <p
          class="mt-5 text-slate-700"
        >
          {{ review.question }}
        </p>

        <div
          class="mt-6 grid gap-4 md:grid-cols-2"
        >
          <div
            :class="
              review.isCorrect
                ? 'border-emerald-200 bg-emerald-50'
                : 'border-red-200 bg-red-50'
            "
            class="rounded-2xl border p-4"
          >
            <p
              class="text-xs font-semibold uppercase"
            >
              Jawaban Siswa
            </p>

            <h2
              class="mt-2 font-bold"
            >
              {{ review.student }}
            </h2>
          </div>

          <div
            class="rounded-2xl border border-blue-200 bg-blue-50 p-4"
          >
            <p
              class="text-xs font-semibold uppercase text-blue-700"
            >
              Jawaban Benar
            </p>

            <h2
              class="mt-2 font-bold text-blue-700"
            >
              {{ review.correct }}
            </h2>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <section
      class="flex flex-col gap-4 lg:flex-row lg:justify-between"
    >
      <!-- RETRY -->
      <Link
        v-if="canRetry"
        href="#"
        class="rounded-2xl border border-slate-200 bg-white px-6 py-4 text-center font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
      >
        🔄 Ulangi Kuis
      </Link>

      <div v-else />

      <!-- CONTINUE -->
      <Link
        v-if="canContinue"
        href="#"
        class="rounded-2xl bg-blue-600 px-6 py-4 text-center font-semibold text-white transition hover:bg-blue-700"
      >
        🚀 Lanjut ke Praktik →
      </Link>

      <button
        v-else
        disabled
        class="cursor-not-allowed rounded-2xl bg-slate-300 px-6 py-4 font-semibold text-slate-500"
      >
        🔒 Praktik Terkunci
      </button>
    </section>
  </div>
</template>