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
  <div class="space-y-6">

    <!-- HEADER -->
    <section
      :class="colorClass"
      class="relative overflow-hidden rounded-3xl bg-linear-to-r p-6 text-white shadow-lg">

      <div
        class="absolute right-0 top-0 h-44 w-44 rounded-full bg-white/10" />

      <div class="relative z-10">

        <span
          class="rounded-full bg-white/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider">

          Pengaturan Assessment

        </span>

        <h1 class="mt-3 text-3xl font-black">
          Pengaturan {{ title }}
        </h1>

      </div>
    </section>

    <!-- CONTENT -->
    <section class="grid gap-6 xl:grid-cols-12">

      <!-- SETTINGS -->
      <div class="xl:col-span-5">
        <AssessmentSettings
          :type="props.type"
          :assessment="props.assessment" />
      </div>

      <!-- FORM -->
      <div class="xl:col-span-7">
        <QuestionForm
          :type="props.type"
          :editing-question="editingQuestion" />
      </div>

    </section>

    <!-- TABLE -->
    <QuestionTable
      :type="props.type"
      :questions="props.questions"
      @edit="handleEdit" />

  </div>
</template>
```
