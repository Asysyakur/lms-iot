<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { computed, ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  assessment: any;
  questions: any[];
  result: any;
  savedAnswers: any[];
}>();

const currentQuestionIndex = ref(0);

const answers = ref<Record<number, string>>({});

props.savedAnswers.forEach((item) => {
  answers.value[item.question_id] =
    item.answer;
});

const currentQuestion = computed(() => {
  return props.questions?.[
    currentQuestionIndex.value
  ];
});

const totalQuestions =
  props.questions.length;

const answeredCount = computed(() => {
  return Object.keys(
    answers.value
  ).length;
});

const progress = computed(() => {
  return (
    (answeredCount.value /
      totalQuestions) *
    100
  );
});

const isFirstQuestion = computed(() => {
  return currentQuestionIndex.value === 0;
});

const isLastQuestion = computed(() => {
  return (
    currentQuestionIndex.value ===
    totalQuestions - 1
  );
});

const selectAnswer = async (
  option: string
) => {
  answers.value[
    currentQuestion.value.id
  ] = option;

  await axios.post(
    '/student/assessments/answer',
    {
      assessment_result_id:
        props.result.id,

      question_id:
        currentQuestion.value.id,

      answer: option,
    }
  );
};

const nextQuestion = () => {
  if (
    currentQuestionIndex.value <
    totalQuestions - 1
  ) {
    currentQuestionIndex.value++;
  }
};

const previousQuestion = () => {
  if (
    currentQuestionIndex.value > 0
  ) {
    currentQuestionIndex.value--;
  }
};

const goToQuestion = (index: number) => {
  currentQuestionIndex.value = index;
};

const submitExam = async () => {
  const response = await axios.post(
    '/student/assessments/submit',
    {
      result_id: props.result.id,
    }
  );

  router.visit(
    `/student/assessments/${props.assessment.type}/result`
  );
};
</script>

<template>
  <div v-if="currentQuestion" class="grid gap-6 xl:grid-cols-12">

    <!-- QUESTION -->
    <div class="rounded-2xl bg-white p-5 shadow-sm xl:col-span-8">
      <p class="mb-6 text-sm font-semibold text-blue-600">
        Soal
        {{ currentQuestionIndex + 1 }}
      </p>

      <h2 class="text-lg font-semibold text-slate-800">
        {{ currentQuestion.question }}
      </h2>

      <!-- OPTIONS -->
      <div class="mt-6 space-y-4">
        <button v-for="option in [
          'A',
          'B',
          'C',
          'D',
        ]" :key="option" @click="selectAnswer(option)"
          class="w-full rounded-2xl border p-4 text-left text-slate-700 transition" :class="answers[currentQuestion.id] === option
            ? 'border-blue-600 bg-blue-50 text-blue-700'
            : 'border-slate-200 hover:border-blue-500 hover:bg-slate-50'
            ">
          <div class="flex items-start gap-4">

            <!-- OPTION BADGE -->
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border text-sm font-bold" :class="answers[currentQuestion.id] === option
              ? 'border-blue-600 bg-blue-600 text-white'
              : 'border-slate-300 text-slate-600'
              ">
              {{ option }}
            </div>

            <!-- OPTION TEXT -->
            <span class="pt-1 text-sm leading-relaxed">
              {{
                currentQuestion[
                `option_${option.toLowerCase()}`
                ]
              }}
            </span>
          </div>
        </button>
      </div>

      <!-- FOOTER -->
      <div class="mt-10 flex items-center justify-between">

        <!-- PREVIOUS -->
        <button @click="previousQuestion" :disabled="isFirstQuestion"
          class="rounded-2xl px-5 py-3 text-sm font-semibold transition-all duration-200" :class="isFirstQuestion
              ? 'cursor-not-allowed border border-slate-200 bg-slate-100 text-slate-400'
              : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600'
            ">
          ← Sebelumnya
        </button>

        <!-- NEXT -->
        <button @click="nextQuestion" :disabled="isLastQuestion"
          class="rounded-2xl px-5 py-3 text-sm font-semibold transition-all duration-200" :class="isLastQuestion
              ? 'cursor-not-allowed bg-blue-300 text-white'
              : 'bg-blue-600 text-white hover:bg-blue-700'
            ">
          Selanjutnya →
        </button>

      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="space-y-6 xl:col-span-4">
      <!-- PROGRESS -->
      <div class="rounded-2xl bg-white p-5 shadow-sm">
        <h2 class="font-bold text-slate-800">
          Progress
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          {{ answeredCount }}
          dari
          {{ totalQuestions }}
          soal dijawab
        </p>

        <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200">
          <div class="h-full rounded-full bg-emerald-500" :style="{
            width:
              progress + '%',
          }" />
        </div>
      </div>

      <!-- NAVIGATION -->
      <div class="rounded-2xl bg-white p-5 shadow-sm">
        <h2 class="mb-5 font-bold text-slate-800">
          Navigasi Soal
        </h2>

        <div class="grid grid-cols-5 gap-3">
          <button v-for="(
question,
  index
            ) in questions" :key="question.id" @click="
              goToQuestion(index)
              " class="flex h-11 w-11 items-center justify-center rounded-xl border text-sm font-semibold transition"
            :class="currentQuestionIndex === index
              ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
              : answers[question.id]
                ? 'border-emerald-500 bg-emerald-500 text-white'
                : 'border-slate-300 bg-white text-slate-700 hover:border-blue-400 hover:text-blue-600'
              ">
            {{ index + 1 }}
          </button>
        </div>

        <!-- SUBMIT -->
        <button @click="submitExam"
          class="mt-8 w-full rounded-2xl bg-red-50 py-3 font-semibold text-red-600 transition hover:bg-red-100">
          🚨 Selesai dan Kumpulkan
        </button>
      </div>
    </div>
  </div>
</template>