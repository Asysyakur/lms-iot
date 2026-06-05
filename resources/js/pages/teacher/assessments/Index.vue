```vue
<!-- resources/js/pages/teacher/assessments/Index.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import { ref } from 'vue';

import AssessmentSettings from './components/AssessmentSettings.vue';

import QuestionForm from './components/QuestionForm.vue';

import QuestionTable from './components/QuestionTable.vue';

defineOptions({
  layout: TeacherSidebarLayout,
});

const editingQuestion = ref(null);

const props = defineProps<{
  type: 'pretest' | 'posttest';
  questions: any[];
  assessment: any;
}>();

const isPretest =
  props.type === 'pretest';

const title = isPretest
  ? 'Pre-test'
  : 'Post-test';

const colorClass = isPretest
  ? 'from-blue-700 to-cyan-500'
  : 'from-emerald-600 to-teal-500';

const handleEdit = (
  question: any
) => {

  editingQuestion.value =
    question;
};
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section :class="colorClass"
      class="relative overflow-hidden rounded-xl bg-linear-to-r px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <!-- BADGE -->
        <span class="rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide">

          Pengaturan Assessment
        </span>

        <!-- TITLE -->
        <h1 class="mt-3 text-2xl font-bold">

          Pengaturan {{ title }}
        </h1>

        <!-- DESC -->
        <p class="mt-1 max-w-2xl text-sm text-white/80">

          Kelola pengaturan assessment,
          tambah soal, dan atur status
          {{ title }}.
        </p>
      </div>
    </section>

    <!-- CONTENT -->
    <section class="grid gap-4 xl:grid-cols-12">

      <!-- SETTINGS -->
      <div class="xl:col-span-5">
        <AssessmentSettings :type="props.type" :assessment="props.assessment" />
      </div>

      <!-- FORM -->
      <div class="xl:col-span-7">
        <QuestionForm :type="props.type" :editing-question="editingQuestion" />
      </div>
    </section>

    <!-- TABLE -->
    <QuestionTable :type="props.type" :questions="props.questions" @edit="handleEdit" />

  </div>
</template>
```
