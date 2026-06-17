<!-- resources/js/pages/student/meetings/practices/Show.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';

import axios from 'axios';

import { toast } from 'vue-sonner';

import StudentSidebarLayout
  from '@/layouts/student/StudentSidebarLayout.vue';

import {
  FlaskConical,
  ExternalLink,
  Link as LinkIcon,
  Lock,
  CheckCircle2,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  meeting: any;
  practice: any;
  submission: any;
}>();

/**
 * LINK
 */
const practiceLink =
  ref(
    props.submission?.project_url || ''
  );

const submissionText =
  ref(
    props.submission?.submission_text || ''
  );

/**
 * SUBMITTED
 */
const submitted =
  ref(
    !!props.submission
  );

/**
 * CURRENT SUBMISSION
 */
const currentSubmission =
  ref(
    props.submission
  );

/**
 * INSTRUCTIONS
 */
const instructions =
  computed(() => {

    if (!props.practice?.instruction) {
      return [];
    }

    return props.practice.instruction
      .split('\n')
      .filter(item =>
        item.trim() !== ''
      );

  });

/**
 * CAN SUBMIT
 */
const canSubmit =
  computed(() => {

    const type =
      props.practice
        ?.submission_type;

    if (type === 'link') {

      return (
        practiceLink.value
          .trim()
          .length > 0
      );
    }

    if (type === 'text') {

      return (
        submissionText.value
          .trim()
          .length > 0
      );
    }

    return (
      practiceLink.value
        .trim()
        .length > 0
      &&
      submissionText.value
        .trim()
        .length > 0
    );
  });

/**
 * LKPD
 */
const lkpdOpened =
  computed(() => {

    return props.meeting
      .lkpd?.is_active;

  });

/**
 * SUBMIT
 */
const submitPractice =
  async () => {

    const request =
      axios.post(
        `/student/meetings/${props.meeting.id}/practice/submit`,
        {
          project_url:
            practiceLink.value,

          submission_text:
            submissionText.value,
        }
      );

    toast.promise(request, {

      loading:
        'Mengumpulkan praktikum...',

      success: () => {

        submitted.value = true;

        currentSubmission.value = {
          project_url:
            practiceLink.value,

          submission_text:
            submissionText.value,
        };

        return 'Praktikum berhasil dikumpulkan';
      },

      error: (error: any) => {

        return (
          error.response?.data?.errors
            ?.project_url?.[0]

          ||

          'Link tidak valid'
        );
      },
    });

    await request;
  };
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-emerald-700 to-cyan-600 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <!-- BADGE -->
        <span class="rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-semibold">

          Pertemuan
          {{ props.meeting.meeting_number }}
        </span>

        <!-- TITLE -->
        <h1 class="mt-3 text-2xl font-bold">

          Praktikum
          {{ props.meeting.title }}
        </h1>

        <!-- DESC -->
        <p class="mt-1 max-w-2xl text-sm text-emerald-100">

          Kerjakan praktikum menggunakan
          MakeCode lalu kumpulkan link
          proyekmu.
        </p>
      </div>
    </section>

    <!-- FLOW -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <div class="flex items-center">

        <!-- MATERI -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Materi
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-emerald-500" />

        <!-- REFLEKSI -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Refleksi
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-emerald-500" />

        <!-- QUIZ -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Kuis
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-blue-500" />

        <!-- PRACTICE -->
        <div class="flex flex-col items-center">

          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">

            4
          </div>

          <p class="mt-2 text-[10px] font-semibold text-blue-600">

            Praktik
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-slate-200" />

        <!-- LKPD -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-500">

            5
          </div>

          <p class="mt-2 text-[10px] font-semibold text-slate-500">

            LKPD
          </p>
        </div>
      </div>
    </section>

    <!-- INSTRUCTIONS -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <!-- TOP -->
      <div class="mb-4 flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">

          <FlaskConical class="h-5 w-5 text-blue-600" />
        </div>

        <div>

          <h2 class="text-sm font-bold text-slate-800">

            Instruksi Praktikum
          </h2>

          <p class="mt-1 text-xs text-slate-500">

            Ikuti langkah berikut.
          </p>
        </div>
      </div>

      <!-- LIST -->
      <div class="space-y-3">

        <div v-for="(instruction, index) in instructions" :key="instruction"
          class="flex items-start gap-3 rounded-xl border border-slate-200 p-3">

          <div
            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-600 text-[11px] font-bold text-white">

            {{ index + 1 }}
          </div>

          <p class="pt-1 text-sm leading-relaxed text-slate-700">

            {{ instruction }}
          </p>
        </div>
      </div>
    </section>

    <!-- MAKECODE -->
    <section v-if="
      practice.submission_type
      !== 'text'
    " class="rounded-xl border border-blue-200 bg-blue-50 p-4">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- LEFT -->
        <div>

          <h2 class="text-sm font-bold text-blue-700">

            Buka MakeCode
          </h2>

          <p class="mt-1 text-xs text-blue-600">

            Gunakan editor MakeCode untuk
            membuat program Microbit.
          </p>
        </div>

        <!-- BUTTON -->
        <a :href="props.practice.makecode_url || 'https://makecode.microbit.org/'" target="_blank"
          class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">

          <ExternalLink class="h-4 w-4" />

          Buka MakeCode
        </a>
      </div>
    </section>

    <!-- WARNING -->
    <section class="rounded-xl border border-amber-200 bg-amber-50 p-4">

      <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100">

          <Lock class="h-4 w-4 text-amber-600" />
        </div>

        <div class="text-amber-600 text-xs">

          <h2 class="text-sm font-bold text-amber-700">

            Ketentuan Pengumpulan
          </h2>

          <p v-if="
            practice.submission_type
            === 'link'
          ">
            Pastikan link MakeCode dapat
            diakses sebelum dikumpulkan.
          </p>

          <p v-else-if="
            practice.submission_type
            === 'text'
          ">
            Pastikan jawaban sudah
            lengkap sebelum dikumpulkan.
          </p>

          <p v-else>
            Pastikan link dan jawaban
            teks sudah lengkap sebelum
            dikumpulkan.
          </p>
        </div>
      </div>
    </section>

    <!-- SUBMIT -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <!-- TOP -->
      <div class="mb-4 flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">

          <LinkIcon class="h-5 w-5 text-emerald-600" />
        </div>

        <div>

          <h2 class="text-sm font-bold text-slate-800">

            Pengumpulan Praktikum
          </h2>

          <p v-if="
            practice.submission_type
            === 'link'
          " class="mt-1 text-xs text-slate-500">
            Tempelkan link MakeCode proyekmu.
          </p>

          <p v-else-if="
            practice.submission_type
            === 'text'
          " class="mt-1 text-xs text-slate-500">
            Tulis jawaban praktikmu.
          </p>

          <p v-else class="mt-1 text-xs text-slate-500">
            Tempelkan link dan isi jawaban praktik.
          </p>
        </div>
      </div>

      <!-- FORM -->
      <div class="space-y-4">

        <!-- LINK -->
        <div v-if="
          practice.submission_type
          === 'link'
          ||
          practice.submission_type
          === 'both'
        ">

          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Link MakeCode
          </label>

          <input v-model="practiceLink" type="text" placeholder="https://makecode.microbit.org/..."
            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500" />

        </div>

        <!-- TEXT -->
        <div v-if="
          practice.submission_type
          === 'text'
          ||
          practice.submission_type
          === 'both'
        ">

          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Jawaban Praktik
          </label>

          <textarea v-model="submissionText" rows="6" placeholder="Tuliskan jawaban praktik..."
            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500" />

        </div>

      </div>

      <div class="flex justify-end">

        <button @click="submitPractice" :disabled="!canSubmit" :class="canSubmit
            ? 'bg-emerald-500 hover:bg-emerald-600'
            : 'cursor-not-allowed bg-slate-300 text-slate-500'
          " class="rounded-lg px-4 py-2 text-sm font-semibold text-white transition">
          {{
            canSubmit
              ? 'Kumpulkan'
              : 'Lengkapi Data'
          }}
        </button>

      </div>

      <!-- SUCCESS -->
      <div v-if="submitted"
        class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">

        <div class="flex items-center gap-2">

          <CheckCircle2 class="h-4 w-4" />

          Praktikum berhasil dikumpulkan.
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <section class="flex justify-end">

      <!-- ACTIVE -->
      <a v-if="
        lkpdOpened &&
        submitted
      " :href="`/student/meetings/${props.meeting.id}/lkpd`"
        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

        Lanjut ke LKPD
      </a>

      <!-- LOCKED -->
      <button v-else disabled
        class="cursor-not-allowed rounded-lg bg-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-500">

        LKPD Terkunci
      </button>
    </section>
  </div>
</template>