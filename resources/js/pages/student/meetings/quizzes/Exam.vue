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
 * INDEX SOAL SEKARANG
 */
const currentQuestionIndex =
  ref(0);

/**
 * SOAL AKTIF
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
 * JAWABAN SISWA
 */
const answers =
  ref<Record<number, string>>({});

/**
 * PILIH JAWABAN
 */
const selectAnswer = (
  questionId: number,
  answer: string,
) => {

  answers.value[questionId] =
    answer;
};

/**
 * TOTAL YANG SUDAH DIJAWAB
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
  <div class="space-y-6">
    <!-- HEADER -->
    <section class="rounded-3xl bg-[#173B74] p-5 text-white shadow-lg">
      <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
            Pertemuan {{ props.meeting.meeting_number }} : {{ props.meeting.title }}
          </span>

          <h1 class="mt-3 text-2xl font-bold">
            🧠 Kuis {{ props.meeting.title || 'Kuis Belum Tersedia' }}
          </h1>
        </div>
      </div>
    </section>

    <!-- CONTENT -->
    <div class="grid gap-6 xl:grid-cols-12">
      <!-- QUESTION -->
      <div v-if="currentQuestion" class="rounded-2xl bg-white p-5 shadow-sm xl:col-span-8">
        <p class="mb-6 text-sm font-semibold text-blue-600">
          Soal {{ currentQuestionIndex + 1 }}
        </p>

        <h2 class="text-lg font-semibold text-slate-800">
          {{ currentQuestion.question }}
        </h2>

        <!-- OPTIONS -->
        <div class="mt-8 space-y-4">
          <button v-for="option in ['a', 'b', 'c', 'd']" :key="option" @click="
            selectAnswer(
              currentQuestion.id,
              option.toUpperCase()
            )
            "
            class="cursor-pointer group flex w-full items-center gap-4 rounded-2xl border p-4 text-left transition-all duration-200"
            :class="answers[currentQuestion.id] ===
              option.toUpperCase()
              ? 'border-blue-500 bg-blue-50'
              : 'border-slate-200 bg-white hover:border-blue-300 hover:bg-slate-50'
              ">
            <!-- CIRCLE -->
            <div
              class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border text-sm font-bold transition"
              :class="answers[currentQuestion.id] ===
                option.toUpperCase()
                ? 'border-blue-500 bg-blue-500 text-white'
                : 'border-slate-300 bg-white text-slate-600 group-hover:border-blue-400'
                ">
              {{ option.toUpperCase() }}
            </div>

            <!-- TEXT -->
            <div class="text-sm font-medium leading-relaxed" :class="answers[currentQuestion.id] ===
              option.toUpperCase()
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
        <div class="mt-10 flex items-center justify-between">
          <button @click="prevQuestion" :disabled="currentQuestionIndex === 0
            " :class="currentQuestionIndex === 0
              ? 'cursor-not-allowed border-slate-200 bg-slate-100 text-slate-400'
              : 'cursor-pointer border-slate-300 bg-white text-slate-700 hover:border-blue-400 hover:bg-blue-50'
              "
            class="rounded-xl border text-slate-700 border-slate-200 px-5 py-3 text-sm font-semibold disabled:opacity-50 ">
            ← Sebelumnya
          </button>

          <button v-if="
            currentQuestionIndex <
            props.questions.length - 1
          " @click="nextQuestion"
            class="cursor-pointer rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white">
            Selanjutnya →
          </button>

          <button v-else @click="submitQuiz"
            class="cursor-pointer rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white">
            🚀 Selesai
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
            {{ answeredQuestions }}
            dari {{ props.questions.length }} soal dijawab
          </p>

          <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200">
            <div class="h-full rounded-full bg-emerald-500" :style="{
              width: progress + '%',
            }" />
          </div>
        </div>

        <!-- NAVIGATION -->
        <div class="rounded-2xl bg-white p-5 shadow-sm">
          <h2 class="mb-5 font-bold text-slate-800">
            Navigasi Soal
          </h2>

          <div class="grid grid-cols-5 gap-3">
            <button v-for="(question, index) in props.questions" :key="question.id"
              @click="currentQuestionIndex = index"
              class="cursor-pointer flex h-11 w-11 items-center justify-center rounded-xl border text-sm font-semibold transition"
              :class="index === currentQuestionIndex
                ? 'border-blue-600 bg-blue-600 text-white'
                : answers[question.id]
                  ? 'border-emerald-500 bg-emerald-500 text-white'
                  : 'border-slate-200 hover:border-blue-500 text-slate-700 hover:bg-blue-50'
                ">
              {{ index + 1 }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>