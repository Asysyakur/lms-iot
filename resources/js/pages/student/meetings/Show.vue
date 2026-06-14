<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
  CheckCircle2,
  BookOpen,
  ClipboardCheck,
  Code2,
  FileSpreadsheet,
  ChevronLeft,
  ChevronRight,
  ClipboardList,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

interface Step {
  id: number;
  title: string;
  description: string;
  icon: any;
  unlocked: boolean;
  completed: boolean;
  active: boolean;
  href?: string;
  teacher_only?: boolean;
}

const props = defineProps<{
  meeting: any;
  steps: Step[];
}>();

const meeting = props.meeting;

const steps = props.steps;

const iconMap = {
  BookOpen,
  ClipboardCheck,
  Code2,
  FileSpreadsheet,
  ClipboardList,
};

const firstStep =
  steps.find(
    step => step.id === 1
  );
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section class="overflow-hidden rounded-xl bg-white p-4 shadow-sm">

      <div class="flex flex-col gap-4 xl:flex-row xl:items-start">

        <!-- IMAGE -->
        <div
          class="flex h-24 w-full items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-cyan-500 xl:h-28 xl:w-40">

          <img src="https://cdn-icons-png.flaticon.com/512/2103/2103832.png" class="h-16 w-16 object-contain">
        </div>

        <!-- CONTENT -->
        <div class="flex-1">

          <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">

            <div>

              <!-- BADGES -->
              <div class="mb-2 flex flex-wrap items-center gap-2">

                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-semibold text-blue-700">

                  {{ meeting.title }}
                </span>

                <span v-if="meeting.opened"
                  class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold text-emerald-700">

                  ● Aktif
                </span>

                <span v-else class="rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-semibold text-red-700">

                  ● Ditutup
                </span>
              </div>

              <!-- TITLE -->
              <h1 class="text-xl font-bold text-slate-800 md:text-2xl">

                {{ meeting.subtitle }}
              </h1>

              <!-- DESC -->
              <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-500">

                {{ meeting.description }}
              </p>
            </div>
          </div>

          <!-- PROGRESS -->
          <div class="mt-5">

            <div class="flex items-start w-full px-6">

              <template v-for="(step, index) in steps" :key="step.id">

                <!-- STEP -->
                <div class="flex flex-col items-center shrink-0">

                  <!-- CIRCLE -->
                  <div :class="step.completed
                    ? 'bg-emerald-500 text-white'
                    : step.active
                      ? 'bg-blue-600 text-white'
                      : 'bg-slate-200 text-slate-500'
                    "
                    class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold shadow-sm">

                    <CheckCircle2 v-if="step.completed" class="h-3.5 w-3.5" />

                    <span v-else>
                      {{ step.id }}
                    </span>
                  </div>

                  <!-- LABEL -->
                  <span class="mt-2 whitespace-nowrap text-center text-[11px] font-medium text-slate-600">

                    {{ step.title }}
                  </span>
                </div>

                <!-- LINE -->
                <div v-if="index !== steps.length - 1" class="mt-4 h-[3px] flex-1 rounded-full bg-slate-200 mx-2">

                  <div v-if="step.completed" class="h-full w-full rounded-full bg-emerald-500" />
                </div>

              </template>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- STEPS -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <h2 class="mb-4 text-[15px] font-bold text-slate-800">

        Tahapan Pembelajaran
      </h2>

      <div class="space-y-3">

        <div v-for="step in steps" :key="step.id"
          class="flex flex-col gap-2 rounded-lg border border-slate-200 px-4 py-3 lg:flex-row lg:items-center lg:justify-between">

          <!-- LEFT -->
          <div class="flex items-center gap-3">

            <!-- NUMBER -->
            <div :class="step.completed
              ? 'bg-emerald-500 text-white'
              : step.active
                ? 'bg-blue-600 text-white'
                : 'bg-slate-200 text-slate-500'
              " class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[11px] font-bold">

              {{ step.id }}
            </div>

            <!-- ICON -->
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">

              <component :is="iconMap[step.icon]" class="h-4 w-4 text-slate-600" />
            </div>

            <!-- CONTENT -->
            <div>

              <h3 class="text-sm font-semibold leading-tight text-slate-800">

                {{ step.title }}
              </h3>

              <p class="mt-0.5 text-xs text-slate-500">

                {{ step.description }}
              </p>
            </div>
          </div>

          <!-- RIGHT -->
          <div class="flex items-center gap-2 lg:flex-col lg:items-end">

            <!-- STATUS -->
            <span v-if="step.completed"
              class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold text-emerald-700">

              ✓ Selesai
            </span>

            <span v-else-if="step.active"
              class="rounded-full bg-blue-100 px-2 py-1 text-[10px] font-semibold text-blue-700">

              ● Terbuka
            </span>

            <span v-else-if="step.teacher_only"
              class="rounded-full bg-orange-100 px-2 py-1 text-[10px] font-semibold text-orange-700">

              🔒 Guru
            </span>

            <span v-else class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600">

              🔒 Terkunci
            </span>

            <!-- BUTTON -->
            <a v-if="step.unlocked" :href="step.href"
              class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600">

              Buka
            </a>

            <button v-else disabled
              class="cursor-not-allowed rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-500">

              Tidak Tersedia
            </button>
          </div>
        </div>
      </div>

      <!-- NOTE -->
      <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-3 text-xs text-blue-700">

        Kamu harus menyelesaikan
        tahapan sebelumnya sebelum
        membuka tahap berikutnya.
      </div>

      <!-- FOOTER -->
      <div class="mt-6 flex items-center justify-between">

        <a href="/student/dashboard"
          class="flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
          <ChevronLeft class="h-4 w-4" />
          Kembali
        </a>

        <!-- ENABLE -->
        <a v-if="firstStep?.unlocked" :href="firstStep.href"
          class="flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600">
          Lanjut ke Materi
          <ChevronRight class="h-4 w-4" />
        </a>

        <!-- DISABLED -->
        <button v-else disabled
          class="cursor-not-allowed flex items-center gap-2 rounded-lg bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500">
          Lanjut ke Materi
          <ChevronRight class="h-4 w-4" />
        </button>

      </div>
    </section>
  </div>
</template>