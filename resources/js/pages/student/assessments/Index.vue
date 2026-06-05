<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

import {
  CheckCircle2,
  AlertCircle,
  ClipboardCheck,
  ShieldCheck,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

interface Requirement {
  title: string;
  completed: boolean;
}

interface Assessment {
  type: 'pretest' | 'posttest';
  title: string;
  description: string;
  duration: number;
  total_questions: number;
  essay_questions: number;
  unlocked: boolean;
  requirements?: Requirement[];
}

const props = defineProps<{
  assessment: Assessment;

  latestResult: any;

  submittedCount: number;

  remainingAttempts: number;

  isOpened: boolean;

  canTakeExam: boolean;

  openAt: string;
}>();

const isPretest =
  props.assessment.type === 'pretest';

const requirements =
  props.assessment.requirements ?? [];

const informations = [
  `${props.assessment.total_questions} soal pilihan ganda + ${props.assessment.essay_questions} soal uraian`,
  `Waktu pengerjaan: ${props.assessment.duration} menit`,
  'Hasil langsung tampil setelah selesai',
];

const pretestRules = [
  'Pre-test hanya dapat dikerjakan 1 kali',
  'Pastikan koneksi internet stabil',
  'Waktu pengerjaan tidak dapat dijeda',
  'Jawaban akan tersimpan otomatis',
  'Kerjakan secara mandiri tanpa bantuan',
];

const posttestRules = [
  'Posttest hanya terbuka jika semua tahapan selesai',
  'Nilai kuis minimal 80',
  'Seluruh LKPD harus dikumpulkan',
  'Guru dapat menutup akses sewaktu-waktu',
  'Hasil posttest mempengaruhi nilai akhir',
];

const hasSubmitted =
  props.submittedCount > 0;
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section class="relative overflow-hidden rounded-2xl bg-[#173B74] px-5 py-4 text-white shadow-lg">

      <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-cyan-400/10" />

      <div>

        <span :class="isPretest
          ? 'bg-blue-100 text-blue-700'
          : 'bg-emerald-100 text-emerald-700'
          " class="rounded-full px-2.5 py-1 text-[10px] font-semibold">

          {{ props.assessment.title }}
        </span>

        <h1 class="mt-3 text-2xl font-bold md:text-3xl">
          {{ props.assessment.title }}
        </h1>

        <p class="mt-1 text-sm text-slate-300">
          {{ props.assessment.description }}
        </p>
      </div>
    </section>

    <!-- CONTENT -->
    <div class="grid gap-4" :class="!isPretest
      ? 'xl:grid-cols-2'
      : 'xl:grid-cols-1'
      ">

      <!-- REQUIREMENTS -->
      <div v-if="!isPretest" class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="mb-4 text-[15px] font-bold text-slate-800">

          Syarat Mengerjakan
          {{ props.assessment.title }}
        </h2>

        <div class="space-y-3">

          <!-- REQUIREMENTS DARI BACKEND -->
          <div v-for="item in requirements" :key="item.title"
            class="flex items-start gap-3 border-b border-slate-100 pb-3">

            <CheckCircle2 v-if="item.completed" class="mt-0.5 h-4 w-4 text-emerald-500" />

            <AlertCircle v-else class="mt-0.5 h-4 w-4 text-red-500" />

            <span :class="item.completed
              ? 'text-slate-700'
              : 'text-red-500'
              " class="text-sm">

              {{ item.title }}
            </span>
          </div>

          <!-- RULES POSTTEST -->
          <div v-for="rule in posttestRules" :key="rule" class="flex items-start gap-3 border-b border-slate-100 pb-3">

            <CheckCircle2 class="mt-0.5 h-4 w-4 text-emerald-500" />

            <span class="text-sm text-slate-700">

              {{ rule }}
            </span>
          </div>
        </div>

        <!-- SUCCESS -->
        <div v-if="props.assessment.unlocked" class="mt-4 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-700">

          ✅ Semua syarat terpenuhi.
          Kamu dapat mengerjakan
          {{ props.assessment.title }}.
        </div>
      </div>

      <!-- INFORMATION -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="mb-4 text-[15px] font-bold text-slate-800">

          Informasi
          {{ props.assessment.title }}
        </h2>

        <div class="space-y-4">

          <div v-for="info in informations" :key="info" class="flex items-start gap-3">

            <ClipboardCheck class="mt-0.5 h-4 w-4 text-blue-500" />

            <span class="text-sm text-slate-700">

              {{ info }}
            </span>
          </div>
        </div>

        <!-- RULES -->
        <div v-if="isPretest" class="mt-5 rounded-xl bg-slate-50 p-3">

          <h3 class="mb-3 text-sm font-bold text-slate-700">

            Ketentuan Tambahan
          </h3>

          <div class="space-y-2.5">

            <div v-for="rule in pretestRules" :key="rule" class="flex items-start gap-2">

              <div class="mt-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500" />

              <span class="text-sm text-slate-600">

                {{ rule }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ACTION -->
    <div :class="isPretest
      ? 'border-blue-200 bg-blue-50'
      : 'border-emerald-200 bg-emerald-50'
      " class="rounded-2xl border p-4 shadow-sm">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-3">

          <div :class="hasSubmitted
            ? 'bg-emerald-500'
            : isPretest
              ? 'bg-blue-600'
              : 'bg-emerald-500'
            " class="flex h-11 w-11 items-center justify-center rounded-xl text-white">

            <ShieldCheck class="h-5 w-5" />
          </div>

          <div>

            <!-- BELUM -->
            <template v-if="!hasSubmitted">

              <h2 :class="isPretest
                ? 'text-blue-700'
                : 'text-emerald-700'
                " class="text-sm font-bold">

                Siap mengerjakan
                {{ props.assessment.title }}?
              </h2>

              <p :class="isPretest
                ? 'text-blue-600'
                : 'text-emerald-600'
                " class="mt-1 text-xs">

                {{
                  props.canTakeExam
                    ? 'Pastikan koneksi stabil dan kerjakan dengan mandiri'
                    : 'Masih ada syarat yang harus diselesaikan'
                }}
              </p>
            </template>

            <!-- SUBMITTED -->
            <template v-else>

              <h2 class="text-sm font-bold text-emerald-700">

                Assessment sudah dikerjakan
              </h2>

              <p class="mt-1 text-xs text-emerald-600">

                Nilai:
                <span class="font-bold">
                  {{ latestResult.score }}
                </span>

                • Benar:
                <span class="font-bold">
                  {{ latestResult.correct_answers }}
                </span>

                • Salah:
                <span class="font-bold">
                  {{ latestResult.wrong_answers }}
                </span>
              </p>
            </template>
          </div>
        </div>

        <!-- BUTTON -->
        <div class="flex flex-col items-end gap-3">

          <!-- BELUM BUKA -->
          <div v-if="!props.isOpened" class="rounded-xl bg-amber-100 px-4 py-2.5 text-xs font-semibold text-amber-700">

            Assessment dibuka pada
            {{ props.openAt }}
          </div>

          <!-- BELUM SYARAT -->
          <div v-else-if="!props.canTakeExam && !hasSubmitted"
            class="rounded-xl bg-red-100 px-4 py-2.5 text-xs font-semibold text-red-700">

            Syarat assessment belum terpenuhi
          </div>

          <!-- ATTEMPT HABIS -->
          <template v-else-if="props.remainingAttempts <= 0">

            <div class="flex flex-wrap items-center gap-3">

              <div class="rounded-xl bg-red-100 px-4 py-2.5 text-xs font-semibold text-red-700">

                Kesempatan pengerjaan habis
              </div>

              <Link :href="`/student/assessments/${props.assessment.type}/result`"
                class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600">

                Lihat Hasil
              </Link>
            </div>
          </template>

          <!-- BISA -->
          <template v-else>

            <div class="flex flex-wrap items-center gap-3">

              <div class="text-xs text-slate-500">

                Kesempatan tersisa:
                <span class="font-bold">
                  {{ props.remainingAttempts }}
                </span>
              </div>

              <Link :href="`/student/assessments/${props.assessment.type}/exam`" :class="isPretest
                ? 'bg-blue-600 hover:bg-blue-700'
                : 'bg-emerald-500 hover:bg-emerald-600'
                "
                class="inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition">

                Mulai
                {{ props.assessment.title }}
              </Link>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>