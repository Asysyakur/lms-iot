<!-- resources/js/pages/student/meetings/quizzes/Exam.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';

import axios from 'axios';

import { router } from '@inertiajs/vue3';

import StudentSidebarLayout
  from '@/layouts/student/StudentSidebarLayout.vue';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  meeting: any;
  questions: any[];
}>();

/**
 * CURRENT QUESTION
 */
const currentQuestionIndex =
  ref(0);

/**
 * ACTIVE QUESTION
 */
const currentQuestion =
  computed(() => {

    if (
      !props.questions ||
      props.questions.length === 0
    ) {
      return null;
    }

    return props.questions[
      currentQuestionIndex.value
    ];

  });

/**
 * ANSWERS
 */
const answers =
  ref<Record<number, string>>({});

/**
 * SELECT
 */
const selectAnswer = (
  questionId: number,
  answer: string,
) => {

  answers.value[questionId] =
    answer;

};

/**
 * ANSWERED
 */
const answeredQuestions =
  computed(() => {

    return Object.keys(
      answers.value
    ).length;

  });

/**
 * PROGRESS
 */
const progress =
  computed(() => {

    if (
      !props.questions ||
      props.questions.length === 0
    ) {
      return 0;
    }

    return Math.round(
      (
        answeredQuestions.value
        /
        props.questions.length
      ) * 100
    );

  });

/**
 * NEXT
 */
const nextQuestion = () => {

  if (
    currentQuestionIndex.value <
    props.questions.length - 1
  ) {

    currentQuestionIndex.value++;

  }

};

/**
 * PREV
 */
const prevQuestion = () => {

  if (
    currentQuestionIndex.value > 0
  ) {

    currentQuestionIndex.value--;

  }

};

/**
 * SUBMIT
 */
const submitQuiz =
  async () => {

    const response =
      await axios.post(
        `/student/meetings/${props.meeting.id}/quiz/submit`,
        {
          answers: answers.value,
        }
      );

    router.visit(
      `/student/meetings/${props.meeting.id}/quiz/review/${response.data.attempt_id}`
    );

  };
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="rounded-xl bg-[#173B74] px-5 py-4 text-white shadow-sm">

      <div
        class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

        <div>

          <!-- BADGE -->
          <span
            class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-semibold text-blue-700">

            Pertemuan
            {{ props.meeting.meeting_number }}
          </span>

          <!-- TITLE -->
          <h1
            class="mt-3 text-2xl font-bold">

            🧠 Kuis
            {{ props.meeting.title || 'Kuis' }}
          </h1>

          <!-- DESC -->
          <p
            class="mt-1 text-sm text-slate-300">

            Pilih jawaban yang paling tepat.
          </p>
        </div>

        <!-- PROGRESS -->
        <div
          class="rounded-xl bg-white/10 px-4 py-3 backdrop-blur-sm">

          <p
            class="text-xs text-slate-300">

            Progress
          </p>

          <h2
            class="mt-1 text-2xl font-bold">

            {{ progress }}%
          </h2>
        </div>
      </div>
    </section>

    <!-- CONTENT -->
    <div
      class="grid gap-4 xl:grid-cols-12">

      <!-- QUESTION -->
      <div
        v-if="currentQuestion"
        class="rounded-xl bg-white p-4 shadow-sm xl:col-span-8">

        <!-- NUMBER -->
        <p
          class="mb-4 text-xs font-semibold text-blue-600">

          Soal
          {{ currentQuestionIndex + 1 }}
          dari
          {{ props.questions.length }}
        </p>

        <!-- QUESTION -->
        <h2
          class="text-base font-semibold leading-relaxed text-slate-800 md:text-lg">

          {{ currentQuestion.question }}
        </h2>

        <!-- OPTIONS -->
        <div
          class="mt-6 space-y-3">

          <button
            v-for="option in ['a', 'b', 'c', 'd']"
            :key="option"
            @click="
              selectAnswer(
                currentQuestion.id,
                option.toUpperCase()
              )
            "
            class="group flex w-full cursor-pointer items-start gap-3 rounded-xl border p-3 text-left transition-all duration-200"
            :class="answers[currentQuestion.id] === option.toUpperCase()
              ? 'border-blue-500 bg-blue-50'
              : 'border-slate-200 bg-white hover:border-blue-300 hover:bg-slate-50'
              ">

            <!-- BADGE -->
            <div
              class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border text-xs font-bold transition"
              :class="answers[currentQuestion.id] === option.toUpperCase()
                ? 'border-blue-500 bg-blue-500 text-white'
                : 'border-slate-300 bg-white text-slate-600 group-hover:border-blue-400'
                ">

              {{ option.toUpperCase() }}
            </div>

            <!-- TEXT -->
            <div
              class="pt-1 text-sm leading-relaxed"
              :class="answers[currentQuestion.id] === option.toUpperCase()
                ? 'text-blue-700'
                : 'text-slate-700'
                ">

              {{
                currentQuestion[
                `option_${option}`
                ]
              }}
            </div>
          </button>
        </div>

        <!-- FOOTER -->
        <div
          class="mt-8 flex items-center justify-between">

          <!-- PREV -->
          <button
            @click="prevQuestion"
            :disabled="currentQuestionIndex === 0"
            :class="currentQuestionIndex === 0
              ? 'cursor-not-allowed border-slate-200 bg-slate-100 text-slate-400'
              : 'cursor-pointer border-slate-300 bg-white text-slate-700 hover:border-blue-400 hover:bg-blue-50'
              "
            class="rounded-lg border px-4 py-2 text-sm font-semibold transition">

            ← Sebelumnya
          </button>

          <!-- NEXT -->
          <button
            v-if="
              currentQuestionIndex <
              props.questions.length - 1
            "
            @click="nextQuestion"
            class="cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">

            Selanjutnya →
          </button>

          <!-- SUBMIT -->
          <button
            v-else
            @click="submitQuiz"
            class="cursor-pointer rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">

            🚀 Selesai
          </button>
        </div>
      </div>

      <!-- SIDEBAR -->
      <div
        class="space-y-4 xl:col-span-4">

        <!-- PROGRESS -->
        <div
          class="rounded-xl bg-white p-4 shadow-sm">

          <h2
            class="text-sm font-bold text-slate-800">

            Progress
          </h2>

          <p
            class="mt-1 text-xs text-slate-500">

            {{ answeredQuestions }}
            dari
            {{ props.questions.length }}
            soal dijawab
          </p>

          <!-- BAR -->
          <div
            class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">

            <div
              class="h-full rounded-full bg-emerald-500 transition-all"
              :style="{
                width: progress + '%',
              }" />
          </div>
        </div>

        <!-- NAVIGATION -->
        <div
          class="rounded-xl bg-white p-4 shadow-sm">

          <h2
            class="mb-4 text-sm font-bold text-slate-800">

            Navigasi Soal
          </h2>

          <div
            class="grid grid-cols-5 gap-2">

            <button
              v-for="(question, index) in props.questions"
              :key="question.id"
              @click="currentQuestionIndex = index"
              class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-lg border text-xs font-semibold transition"
              :class="index === currentQuestionIndex
                ? 'border-blue-600 bg-blue-600 text-white'
                : answers[question.id]
                  ? 'border-emerald-500 bg-emerald-500 text-white'
                  : 'border-slate-200 text-slate-700 hover:border-blue-500 hover:bg-blue-50'
                ">

              {{ index + 1 }}
            </button>
          </div>

          <!-- INFO -->
          <div
            class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-3 text-xs text-blue-700">

            💡 Pastikan semua soal
            sudah dijawab sebelum
            mengumpulkan kuis.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>