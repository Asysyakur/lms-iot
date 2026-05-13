<!-- resources/js/pages/student/assessments/Exam.vue -->

<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { Lock } from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  type: 'pretest' | 'posttest';
  unlocked: boolean;
}>();

const isPretest =
  props.type === 'pretest';

const title = isPretest
  ? 'Pre-test'
  : 'Posttest';

const questions = Array.from(
  { length: 25 },
  (_, i) => i + 1,
);

const currentQuestion = 7;
</script>

<template>
  <div class="space-y-6">
    <!-- HEADER -->
    <section class="rounded-3xl bg-[#173B74] p-5 text-white shadow-lg">
      <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <span :class="isPretest
              ? 'bg-blue-100 text-blue-700'
              : 'bg-emerald-100 text-emerald-700'
            " class="rounded-full px-3 py-1 text-xs font-semibold">
            {{ title }}
          </span>

          <h1 class="mt-3 text-2xl font-bold">
            📝 {{ title }}
          </h1>

          <p class="mt-1 text-sm text-slate-300">
            Kerjakan dengan teliti dan mandiri
          </p>
        </div>

        <!-- TIMER -->
        <div class="rounded-2xl bg-white p-4 text-center text-slate-800">
          <p class="text-xs text-slate-500">
            Sisa Waktu
          </p>

          <h2 class="mt-1 text-3xl font-bold text-blue-600">
            35:42
          </h2>
        </div>
      </div>
    </section>
    <!-- LOCKED -->
    <div v-if="!props.unlocked" class="rounded-3xl bg-white p-10 shadow-sm">
      <div class="flex flex-col items-center justify-center text-center">
        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-orange-100">
          <Lock class="h-12 w-12 text-orange-500" />
        </div>

        <h2 class="mt-6 text-3xl font-bold text-slate-800">
          {{ title }} Belum Tersedia
        </h2>

        <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-500">
          Kamu belum memenuhi syarat untuk
          membuka {{ title }}.
        </p>

        <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700">
          ⚠️ Selesaikan seluruh syarat terlebih
          dahulu sebelum memulai ujian.
        </div>
      </div>
    </div>

    <!-- EXAM -->
    <div v-else class="grid gap-6 xl:grid-cols-12">

      <!-- CONTENT -->
      <div class="grid gap-6 xl:grid-cols-12">
        <!-- QUESTION -->
        <div class="rounded-3xl bg-white p-6 shadow-sm xl:col-span-8">
          <p class="mb-6 text-sm font-semibold text-blue-600">
            Soal 7
          </p>

          <div class="rounded-2xl bg-slate-50 p-5 font-mono text-sm">
            print("Hello World")
          </div>

          <h2 class="mt-6 text-lg font-semibold text-slate-800">
            Apa output dari kode di atas?
          </h2>

          <!-- OPTIONS -->
          <div class="mt-6 space-y-4">
            <button
              class="w-full rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-500 hover:bg-blue-50">
              A. Hello World
            </button>

            <button
              class="w-full rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-500 hover:bg-blue-50">
              B. Syntax Error
            </button>

            <button
              class="w-full rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-500 hover:bg-blue-50">
              C. Undefined
            </button>

            <button
              class="w-full rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-500 hover:bg-blue-50">
              D. Null
            </button>
          </div>

          <!-- FOOTER -->
          <div class="mt-10 flex items-center justify-between">
            <button class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold">
              ← Soal Sebelumnya
            </button>

            <button class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white">
              Soal Selanjutnya →
            </button>
          </div>
        </div>

        <!-- SIDEBAR -->
        <div class="space-y-6 xl:col-span-4">
          <!-- PROGRESS -->
          <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="font-bold text-slate-800">
              Progress
            </h2>

            <p class="mt-1 text-sm text-slate-500">
              7 dari 25 soal dijawab
            </p>

            <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200">
              <div class="h-full w-[28%] rounded-full bg-emerald-500" />
            </div>
          </div>

          <!-- NAVIGATION -->
          <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 font-bold text-slate-800">
              Navigasi Soal
            </h2>

            <div class="grid grid-cols-5 gap-3">
              <button v-for="number in questions" :key="number"
                class="flex h-11 w-11 items-center justify-center rounded-xl border text-sm font-semibold transition"
                :class="number === currentQuestion
                    ? 'bg-blue-600 text-white border-blue-600'
                    : number <= 6
                      ? 'bg-emerald-500 text-white border-emerald-500'
                      : 'border-slate-200 hover:border-blue-500'
                  ">
                {{ number }}
              </button>
            </div>

            <!-- FINISH -->
            <button
              class="mt-8 w-full rounded-2xl bg-red-50 py-3 font-semibold text-red-600 transition hover:bg-red-100">
              🚨 Selesai dan Kumpulkan
            </button>
          </div>
        </div>
      </div>
      SEMUA CONTENT EXAM KAMU DI SINI
    </div>
  </div>
</template>