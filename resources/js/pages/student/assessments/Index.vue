<!-- resources/js/pages/student/assessments/Index.vue -->

<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { Link } from '@inertiajs/vue3';
import {
  CheckCircle2,
  AlertCircle,
  ClipboardCheck,
  ShieldCheck,
  Lock,
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
}>();

const isPretest =
  props.assessment.type === 'pretest';

const requirements =
  props.assessment.requirements ?? [];

const allCompleted = requirements.every(
  (item) => item.completed,
);

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

</script>

<template>
  <div class="space-y-6">
    <!-- HEADER -->
    <section class="relative overflow-hidden rounded-3xl bg-[#173B74] p-6 text-white shadow-lg">
      <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-cyan-400/10" />

      <div>
        <span :class="isPretest
          ? 'bg-blue-100 text-blue-700'
          : 'bg-emerald-100 text-emerald-700'
          " class="rounded-full px-3 py-1 text-xs font-semibold">
          {{ props.assessment.title }}
        </span>

        <h1 class="mt-4 text-3xl font-bold">
          📝 {{ props.assessment.title }}
        </h1>

        <p class="mt-2 text-slate-300">
          {{ props.assessment.description }}
        </p>
      </div>
    </section>

    <!-- CONTENT -->
    <div class="grid gap-6 xl:grid-cols-2">
      <!-- REQUIREMENTS -->
      <div class="rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="mb-6 font-bold text-slate-800">
          ✅ Syarat Mengerjakan
          {{ props.assessment.title }}
        </h2>

        <div class="space-y-4">
          <div v-for="item in requirements" :key="item.title"
            class="flex items-start gap-3 border-b border-slate-100 pb-4">
            <CheckCircle2 v-if="item.completed" class="mt-0.5 h-5 w-5 text-emerald-500" />

            <AlertCircle v-else class="mt-0.5 h-5 w-5 text-red-500" />

            <span :class="item.completed
              ? 'text-slate-700'
              : 'text-red-500'
              " class="text-sm">
              {{ item.title }}
            </span>
          </div>
        </div>

        <!-- SUCCESS -->
        <div v-if="props.assessment.unlocked" class="mt-6 rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-700">
          ✅ Semua syarat terpenuhi.
          Kamu dapat mengerjakan
          {{ props.assessment.title }}.
        </div>
      </div>

      <!-- INFORMATION -->
      <div class="rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="mb-6 font-bold text-slate-800">
          ℹ️ Informasi
          {{ props.assessment.title }}
        </h2>

        <div class="space-y-5">
          <div v-for="info in informations" :key="info" class="flex items-start gap-3">
            <ClipboardCheck class="mt-0.5 h-4 w-4 text-blue-500" />

            <span class="text-sm text-slate-700">
              {{ info }}
            </span>
          </div>
        </div>

        <!-- RULES -->
        <div class="mt-8 rounded-2xl bg-slate-50 p-4">
          <h3 class="mb-4 text-sm font-bold text-slate-700">
            📌 Ketentuan Tambahan
          </h3>

          <div class="space-y-3">
            <div v-for="rule in isPretest
              ? pretestRules
              : posttestRules" :key="rule" class="flex items-start gap-2">
              <div class="mt-1.5 h-2 w-2 rounded-full bg-emerald-500" />

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
      " class="rounded-3xl border p-5 shadow-sm">
      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-4">

          <div :class="isPretest ? 'bg-blue-600' : 'bg-emerald-500'"
            class="flex h-12 w-12 items-center justify-center rounded-2xl text-white">
            <ShieldCheck class="h-6 w-6" />
          </div>

          <div>
            <h2 :class="isPretest
                ? 'text-blue-700'
                : 'text-emerald-700'
              " class="font-bold">
              Siap mengerjakan
              {{ props.assessment.title }}?
            </h2>

            <p :class="isPretest
                ? 'text-blue-600'
                : 'text-emerald-600'
              " class="text-sm">
              {{
                props.assessment.unlocked
                  ? 'Pastikan koneksi stabil dan kerjakan dengan mandiri'
                  : 'Masih ada syarat yang harus diselesaikan'
              }}
            </p>
          </div>
        </div>

        <!-- BUTTON -->
        <Link :href="`/assessments/${props.assessment.type}/exam`" :class="props.assessment.unlocked
            ? isPretest
              ? 'bg-blue-600 hover:bg-blue-700'
              : 'bg-emerald-500 hover:bg-emerald-600'
            : 'bg-slate-300 text-slate-600 hover:bg-slate-300'
          " class="inline-flex items-center justify-center rounded-2xl px-6 py-3 font-semibold text-white transition">
        {{
          props.assessment.unlocked
            ? `🚀 Mulai ${props.assessment.title}`
            : `🔒 Lihat ${props.assessment.title}`
        }}
        </Link>

      </div>
    </div>
  </div>
</template>