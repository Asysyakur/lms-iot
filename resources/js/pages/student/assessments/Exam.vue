<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
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

const calculateRemainingTime =
  () => {

    const startedAt =
      new Date(
        props.result.started_at
      ).getTime();

    const now =
      new Date().getTime();

    const durationMs =
      props.assessment.duration
      * 60
      * 1000;

    const endTime =
      startedAt + durationMs;

    const remaining =
      Math.floor(
        (
          endTime - now
        ) / 1000
      );

    return remaining > 0
      ? remaining
      : 0;
  };

const timeLeft =
  ref(
    calculateRemainingTime()
  );
let timer:
  ReturnType<typeof setInterval>;

props.savedAnswers.forEach((item) => {
  answers.value[item.question_id] = item.answer;
});

const formattedTime =
  computed(() => {

    const minutes =
      Math.floor(
        timeLeft.value / 60
      );

    const seconds =
      timeLeft.value % 60;

    return `${minutes
      .toString()
      .padStart(2, '0')}:${seconds
        .toString()
        .padStart(2, '0')}`;
  });


const currentQuestion = computed(() => {
  return props.questions?.[currentQuestionIndex.value];
});

const totalQuestions = props.questions.length;

const answeredCount = computed(() => {
  return Object.keys(answers.value).length;
});

const progress = computed(() => {
  return (answeredCount.value / totalQuestions) * 100;
});

const canSubmit = computed(() => {
  return totalQuestions > 0 && answeredCount.value === totalQuestions;
});

const isFirstQuestion = computed(() => {
  return currentQuestionIndex.value === 0;
});

const isLastQuestion = computed(() => {
  return currentQuestionIndex.value === totalQuestions - 1;
});

const selectAnswer = async (option: string) => {
  answers.value[currentQuestion.value.id] = option;

  await axios.post('/student/assessments/answer', {
    assessment_result_id: props.result.id,
    question_id: currentQuestion.value.id,
    answer: option,
  });
};

const nextQuestion = () => {
  if (currentQuestionIndex.value < totalQuestions - 1) {
    currentQuestionIndex.value++;
  }
};

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--;
  }
};

const goToQuestion = (index: number) => {
  currentQuestionIndex.value = index;
};

const submitExam = async (
  withConfirmation = true
) => {

  if (submitting.value) {
    return;
  }

  if (!canSubmit.value) {
    window.alert('Jawab semua soal terlebih dahulu sebelum mengumpulkan assessment.');
    return;
  }

  if (withConfirmation) {

    const confirmed =
      window.confirm(
        'Yakin ingin mengumpulkan assessment?'
      );

    if (!confirmed) {
      return;
    }
  }

  submitting.value = true;

  try {

    await axios.post(
      '/student/assessments/submit',
      {
        result_id:
          props.result.id,
      }
    );

    router.visit(
      `/student/assessments/${props.assessment.type}/result`
    );

  } finally {

    submitting.value = false;
  }
};

onMounted(() => {

  timer = setInterval(
    async () => {

      timeLeft.value =
        calculateRemainingTime();

      if (
        timeLeft.value <= 0
      ) {

        clearInterval(timer);

        await submitExam(false);
      }
    },
    1000
  );
});

onUnmounted(() => {

  clearInterval(timer);
});

const submitting =
  ref(false);
</script>

<template>
  <div v-if="currentQuestion" class="grid gap-4 xl:grid-cols-12">

    <!-- QUESTION -->
    <div class="rounded-xl bg-white p-4 shadow-sm xl:col-span-8">

      <p class="mb-4 text-xs font-semibold text-blue-600">
        Soal {{ currentQuestionIndex + 1 }}
      </p>

      <h2 class="whitespace-pre-line text-[15px] font-semibold leading-relaxed text-slate-800 md:text-lg">
        {{ currentQuestion.question }}
      </h2>

      <!-- CODE -->
      <pre v-if="currentQuestion.code"
        class="mt-4 overflow-x-auto rounded-xl bg-slate-900 p-4 text-sm leading-relaxed text-emerald-300"><code>{{ currentQuestion.code }}</code></pre>

      <!-- OPTIONS -->
      <div class="mt-5 space-y-3">

        <button v-for="option in ['A', 'B', 'C', 'D']" :key="option" @click="selectAnswer(option)"
          class="w-full rounded-xl border p-3 text-left transition" :class="answers[currentQuestion.id] === option
            ? 'border-blue-600 bg-blue-50'
            : 'border-slate-200 hover:border-blue-400 hover:bg-slate-50'
            ">

          <div class="flex items-start gap-3">

            <!-- BADGE -->
            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-xs font-bold" :class="answers[currentQuestion.id] === option
              ? 'border-blue-600 bg-blue-600 text-white'
              : 'border-slate-300 text-slate-600'
              ">
              {{ option }}
            </div>

            <!-- TEXT -->
            <span class="pt-0.5 text-sm leading-relaxed text-slate-700">
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
      <div class="mt-7 flex items-center justify-between">

        <button @click="previousQuestion" :disabled="isFirstQuestion"
          class="rounded-xl px-4 py-2.5 text-sm font-semibold transition" :class="isFirstQuestion
            ? 'cursor-not-allowed bg-slate-100 text-slate-400'
            : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-400 hover:text-blue-600'
            ">
          ← Sebelumnya
        </button>

        <button @click="nextQuestion" :disabled="isLastQuestion"
          class="rounded-xl px-4 py-2.5 text-sm font-semibold transition" :class="isLastQuestion
            ? 'cursor-not-allowed bg-blue-300 text-white'
            : 'bg-blue-600 text-white hover:bg-blue-700'
            ">
          Selanjutnya →
        </button>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="space-y-4 xl:col-span-4">

      <!-- PROGRESS -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <div class="mb-4 rounded-xl bg-red-50 p-4">

          <p class="text-xs font-semibold text-red-500">

            Sisa Waktu
          </p>

          <h2 class="mt-1 text-3xl font-bold text-red-600">

            {{ formattedTime }}
          </h2>

          <p v-if="timeLeft <= 300" class="mt-2 text-xs font-semibold text-red-400">

            Waktu hampir habis!
          </p>
        </div>

        <h2 class="text-[15px] font-bold text-slate-800">
          Progress
        </h2>

        <p class="mt-1 text-xs text-slate-500">
          {{ answeredCount }}
          dari
          {{ totalQuestions }}
          soal dijawab
        </p>

        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">

          <div class="h-full rounded-full bg-emerald-500" :style="{
            width: progress + '%',
          }" />
        </div>
      </div>

      <!-- NAVIGATION -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="mb-4 text-[15px] font-bold text-slate-800">
          Navigasi Soal
        </h2>

        <div class="grid grid-cols-5 gap-2">

          <button v-for="(question, index) in questions" :key="question.id" @click="goToQuestion(index)"
            class="flex h-9 w-9 items-center justify-center rounded-lg border text-xs font-semibold transition" :class="currentQuestionIndex === index
              ? 'border-blue-600 bg-blue-600 text-white'
              : answers[question.id]
                ? 'border-emerald-500 bg-emerald-500 text-white'
                : 'border-slate-300 bg-white text-slate-700 hover:border-blue-400 hover:text-blue-600'
              ">

            {{ index + 1 }}
          </button>
        </div>

        <!-- SUBMIT -->
        <button @click="submitExam(true)" :disabled="submitting || !canSubmit"
          class="mt-6 w-full rounded-xl py-2.5 text-sm font-semibold transition" :class="submitting || !canSubmit
            ? 'cursor-not-allowed bg-slate-200 text-slate-500'
            : 'bg-red-50 text-red-600 hover:bg-red-100'
            ">

          Selesai dan Kumpulkan
        </button>

        <p v-if="!canSubmit" class="mt-2 text-center text-xs text-slate-500">
          Jawab semua soal untuk dapat mengumpulkan assessment.
        </p>
      </div>
    </div>
  </div>
</template>
