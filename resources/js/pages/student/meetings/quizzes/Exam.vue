<!-- resources/js/pages/student/meetings/quizzes/Exam.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

defineOptions({
  layout: StudentSidebarLayout,
});

const questions = Array.from(
  { length: 10 },
  (_, i) => i + 1,
);

const currentQuestion = ref(4);

const answeredQuestions = ref([1, 2, 3]);

const progress = computed(() => {
  return Math.round(
    (answeredQuestions.value.length / 10) * 100,
  );
});
</script>

<template>
  <div class="space-y-6">
    <!-- HEADER -->
    <section
      class="rounded-3xl bg-[#173B74] p-5 text-white shadow-lg"
    >
      <div
        class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
      >
        <div>
          <span
            class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700"
          >
            Pertemuan 1
          </span>

          <h1
            class="mt-3 text-2xl font-bold"
          >
            🧠 Kuis Pertemuan
          </h1>
        </div>

        <div
          class="rounded-2xl bg-white p-4 text-center text-slate-800"
        >
          <p class="text-xs text-slate-500">
            Sisa Waktu
          </p>

          <h2
            class="mt-1 text-3xl font-bold text-blue-600"
          >
            12:42
          </h2>
        </div>
      </div>
    </section>

    <!-- CONTENT -->
    <div class="grid gap-6 xl:grid-cols-12">
      <!-- QUESTION -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm xl:col-span-8"
      >
        <p
          class="mb-6 text-sm font-semibold text-blue-600"
        >
          Soal {{ currentQuestion }}
        </p>

        <h2
          class="text-lg font-semibold text-slate-800"
        >
          Fungsi utama sensor cahaya pada
          Micro:bit adalah...
        </h2>

        <!-- OPTIONS -->
        <div class="mt-8 space-y-4">
          <button
            class="w-full rounded-2xl border border-slate-200 p-5 text-left transition hover:border-blue-500 hover:bg-blue-50"
          >
            A. Mengukur suhu lingkungan
          </button>

          <button
            class="w-full rounded-2xl border border-blue-500 bg-blue-50 p-5 text-left"
          >
            B. Mendeteksi intensitas cahaya
          </button>

          <button
            class="w-full rounded-2xl border border-slate-200 p-5 text-left transition hover:border-blue-500 hover:bg-blue-50"
          >
            C. Mengukur tekanan udara
          </button>

          <button
            class="w-full rounded-2xl border border-slate-200 p-5 text-left transition hover:border-blue-500 hover:bg-blue-50"
          >
            D. Mengontrol motor
          </button>
        </div>

        <!-- FOOTER -->
        <div
          class="mt-10 flex items-center justify-between"
        >
          <button
            class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold"
          >
            ← Sebelumnya
          </button>

          <button
            class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white"
          >
            Selanjutnya →
          </button>
        </div>
      </div>

      <!-- SIDEBAR -->
      <div class="space-y-6 xl:col-span-4">
        <!-- PROGRESS -->
        <div
          class="rounded-3xl bg-white p-6 shadow-sm"
        >
          <h2
            class="font-bold text-slate-800"
          >
            Progress
          </h2>

          <p
            class="mt-1 text-sm text-slate-500"
          >
            {{ answeredQuestions.length }}
            dari 10 soal dijawab
          </p>

          <div
            class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200"
          >
            <div
              class="h-full rounded-full bg-emerald-500"
              :style="{
                width: progress + '%',
              }"
            />
          </div>
        </div>

        <!-- NAVIGATION -->
        <div
          class="rounded-3xl bg-white p-6 shadow-sm"
        >
          <h2
            class="mb-5 font-bold text-slate-800"
          >
            Navigasi Soal
          </h2>

          <div
            class="grid grid-cols-5 gap-3"
          >
            <button
              v-for="number in questions"
              :key="number"
              class="flex h-11 w-11 items-center justify-center rounded-xl border text-sm font-semibold transition"
              :class="
                number === currentQuestion
                  ? 'bg-blue-600 text-white border-blue-600'
                  : answeredQuestions.includes(
                      number,
                    )
                  ? 'bg-emerald-500 text-white border-emerald-500'
                  : 'border-slate-200 hover:border-blue-500'
              "
            >
              {{ number }}
            </button>
          </div>

          <!-- SUBMIT -->
          <button
            class="mt-8 w-full rounded-2xl bg-red-50 py-3 font-semibold text-red-600 transition hover:bg-red-100"
          >
            🚨 Selesai dan Kumpulkan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>