<!-- resources/js/pages/student/meetings/quizzes/Review.vue -->

<script setup lang="ts">
import { computed } from 'vue';

import { Link } from '@inertiajs/vue3';

import StudentSidebarLayout
  from '@/layouts/student/StudentSidebarLayout.vue';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  score: number;
  correctAnswers: number;
  wrongAnswers: number;
  attempts: number;
  meetingId: number;

  reviews: {
    question: string;
    code?: string | null;
    student: string;
    correct: string;
    isCorrect: boolean;
  }[];
}>();

const minimumScore = 80;

/**
 * PASSED
 */
const passed =
  computed(() => {

    return (
      props.score >=
      minimumScore
    );

  });

/**
 * RETRY
 */
const canRetry =
  computed(() => {

    return (
      props.score < 80
      &&
      props.attempts < 3
    );

  });

/**
 * CONTINUE
 */
const canContinue =
  computed(() => {

    return (
      props.score >= 80
      ||
      props.attempts >= 3
    );

  });
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-cyan-400/10" />

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- LEFT -->
        <div>
          <h1 class="mt-3 text-2xl font-bold">

            Review Jawaban
          </h1>

          <p class="mt-1 text-sm text-slate-300">

            Berikut hasil pengerjaan
            kuis kamu.
          </p>
        </div>

        <!-- SCORE -->
        <div class="rounded-xl bg-white/10 px-5 py-4 text-center backdrop-blur-sm">

          <p class="text-xs text-slate-300">

            Nilai
          </p>

          <h2 class="mt-1 text-4xl font-bold">

            {{ props.score }}
          </h2>
        </div>
      </div>
    </section>

    <!-- STATS -->
    <div class="grid gap-4 md:grid-cols-4">

      <!-- CORRECT -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="text-3xl font-bold text-emerald-500">

          {{ props.correctAnswers }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Jawaban Benar
        </p>
      </div>

      <!-- WRONG -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="text-3xl font-bold text-red-500">

          {{ props.wrongAnswers }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Jawaban Salah
        </p>
      </div>

      <!-- MIN -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="text-3xl font-bold text-blue-500">

          {{ minimumScore }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Nilai Minimum
        </p>
      </div>

      <!-- ATTEMPTS -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="text-3xl font-bold text-blue-500">

          {{ props.attempts }}/3
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Percobaan
        </p>
      </div>
    </div>

    <!-- STATUS -->
    <section :class="passed
      ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
      : 'border-red-200 bg-red-50 text-red-700'
      " class="rounded-xl border p-4">

      <h2 class="text-sm font-bold">

        {{
          passed
            ? 'Selamat! Kamu lulus kuis.'
            : 'Nilai belum memenuhi syarat.'
        }}
      </h2>

      <p class="mt-1 text-xs opacity-80">

        {{
          passed
            ? 'Tahap berikutnya berhasil dibuka.'
            : 'Kamu masih bisa mencoba lagi.'
        }}
      </p>
    </section>

    <!-- REVIEW -->
    <section class="space-y-4">

      <div v-for="(review, index) in props.reviews" :key="index" :class="review.isCorrect
        ? 'border-emerald-200'
        : 'border-red-200'
        " class="rounded-xl border bg-white p-4 shadow-sm">

        <!-- TOP -->
        <div class="flex items-center justify-between">

          <h2 class="text-sm font-bold text-slate-800">

            Soal
            {{ index + 1 }}
          </h2>

          <span :class="review.isCorrect
            ? 'bg-emerald-100 text-emerald-700'
            : 'bg-red-100 text-red-700'
            " class="rounded-full px-2.5 py-1 text-[10px] font-semibold">

            {{
              review.isCorrect
                ? 'Benar'
                : 'Salah'
            }}
          </span>
        </div>

        <!-- QUESTION -->
        <p class="mt-4 text-sm leading-relaxed text-slate-700">

          {{ review.question }}
        </p>

        <!-- CODE -->
        <pre v-if="review.code"
          class="mt-3 overflow-x-auto rounded-xl bg-slate-900 p-4 text-sm leading-relaxed text-emerald-300"><code>{{ review.code }}</code></pre>

        <!-- ANSWERS -->
        <div class="mt-4 grid gap-3 md:grid-cols-2">

          <!-- STUDENT -->
          <div :class="review.isCorrect
            ? 'border-emerald-200 bg-emerald-50'
            : 'border-red-200 bg-red-50'
            " class="rounded-xl border p-3">

            <p :class="review.isCorrect
              ? 'text-emerald-700'
              : 'text-red-700'
              " class="text-[10px] font-semibold uppercase">

              Jawaban Siswa
            </p>

            <h2 :class="review.isCorrect
              ? 'text-emerald-700'
              : 'text-red-700'
              " class="mt-2 text-sm font-bold">

              {{ review.student }}
            </h2>
          </div>

          <!-- CORRECT -->
          <div class="rounded-xl border border-blue-200 bg-blue-50 p-3">

            <p class="text-[10px] font-semibold uppercase text-blue-700">

              Jawaban Benar
            </p>

            <h2 class="mt-2 text-sm font-bold text-blue-700">

              {{ review.correct }}
            </h2>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <section class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <!-- RETRY -->
      <Link v-if="canRetry" :href="`/student/meetings/${props.meetingId}/quiz`"
        class="rounded-lg border border-slate-200 bg-white px-5 py-3 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

        Ulangi Kuis
      </Link>

      <div v-else />

      <!-- CONTINUE -->
      <Link v-if="canContinue" :href="`/student/meetings/${props.meetingId}/practice`"
        class="rounded-lg bg-blue-600 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-700">

        Lanjut ke Praktik →
      </Link>

      <!-- LOCKED -->
      <button v-else disabled
        class="cursor-not-allowed rounded-lg bg-slate-300 px-5 py-3 text-sm font-semibold text-slate-500">

        Praktik Terkunci
      </button>
    </section>
  </div>
</template>