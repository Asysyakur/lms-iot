<!-- resources/js/pages/teacher/assessments/Index.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import AssessmentSettings from './components/AssessmentSettings.vue';
import QuestionForm from './components/QuestionForm.vue';
import QuestionTable from './components/QuestionTable.vue';

defineOptions({
  layout: TeacherSidebarLayout,
});

const props = defineProps<{
  type: 'pretest' | 'posttest';
}>();

const isPretest =
  props.type === 'pretest';

const title = isPretest
  ? 'Pre-test'
  : 'Post-test';

const colorClass = isPretest
  ? 'from-blue-700 to-cyan-500'
  : 'from-emerald-600 to-teal-500';

const questions = [
  {
    id: 1,
    question:
      'Apa fungsi utama sensor dalam sistem IoT?',
    answer: 'A',
    type: 'Pilihan Ganda',
    status: 'Aktif',
  },

  {
    id: 2,
    question:
      'Jelaskan alur kerja input-process-output pada Micro:bit.',
    answer: 'Rubrik',
    type: 'Uraian',
    status: 'Aktif',
  },
];
</script>

<template>
  <div class="space-y-8">
    <!-- HEADER -->
    <section
      :class="colorClass"
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r p-8 text-white shadow-xl"
    >
      <div
        class="absolute right-0 top-0 h-52 w-52 rounded-full bg-white/10"
      />

      <div
        class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
      >
        <div>
          <span
            class="rounded-full bg-white/20 px-4 py-1 text-xs font-bold uppercase tracking-wider"
          >
            Pengaturan Assessment
          </span>

          <h1
            class="mt-4 text-4xl font-black"
          >
            📝 Pengaturan
            {{ title }}
          </h1>

          <p
            class="mt-3 max-w-3xl text-lg text-slate-100"
          >
            Atur waktu pengerjaan,
            tambahkan soal, dan
            tentukan kunci jawaban.
          </p>
        </div>

        <button
          class="rounded-2xl bg-white/20 px-6 py-4 font-bold text-white backdrop-blur transition hover:bg-white/30"
        >
          + Tambah Soal
          {{ title }}
        </button>
      </div>
    </section>

    <!-- CONTENT -->
    <section
      class="grid gap-6 xl:grid-cols-12"
    >
      <!-- SETTINGS -->
      <div class="xl:col-span-5">
        <AssessmentSettings
          :type="props.type"
        />
      </div>

      <!-- FORM -->
      <div class="xl:col-span-7">
        <QuestionForm
          :type="props.type"
        />
      </div>
    </section>

    <!-- TABLE -->
    <QuestionTable
      :type="props.type"
      :questions="questions"
    />
  </div>
</template>