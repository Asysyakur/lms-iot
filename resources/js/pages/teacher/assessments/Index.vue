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
  classOptions: string[];
  selectedClass: string;
}>();

const isPretest =
  props.type === 'pretest';

const title = isPretest
  ? 'Pre-test'
  : 'Post-test';

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
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <!-- TITLE -->
        <h1 class="text-2xl font-bold">

          Pengaturan {{ title }}
        </h1>
      </div>
    </section>

    <!-- CONTENT -->
    <section class="grid gap-4 xl:grid-cols-12">

      <!-- SETTINGS -->
      <div class="xl:col-span-5">
        <AssessmentSettings
          :type="props.type"
          :assessment="props.assessment"
          :class-options="props.classOptions"
          :selected-class="props.selectedClass" />
      </div>

      <!-- FORM -->
      <div class="xl:col-span-7">
        <QuestionForm
          :type="props.type"
          :selected-class="props.selectedClass"
          :editing-question="editingQuestion" />
      </div>
    </section>

    <!-- TABLE -->
    <QuestionTable :type="props.type" :questions="props.questions" @edit="handleEdit" />

  </div>
</template>
```
