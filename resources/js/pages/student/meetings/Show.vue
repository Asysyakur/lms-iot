<!-- resources/js/pages/student/meetings/Show.vue -->

<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
  CheckCircle2,
  Lock,
  BookOpen,
  ClipboardCheck,
  Code2,
  FileSpreadsheet,
  ChevronLeft,
  ChevronRight,
  ClipboardList,
  Wifi,
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

</script>

<template>
  <div class="space-y-6">
    <!-- HEADER -->
    <section class="overflow-hidden rounded-3xl bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-6 xl:flex-row xl:items-start">
        <!-- IMAGE -->
        <div
          class="flex h-32 w-full items-center justify-center rounded-2xl bg-linear-to-br from-emerald-500 to-cyan-500 xl:h-36 xl:w-48">
          <img src="https://cdn-icons-png.flaticon.com/512/2103/2103832.png" class="h-24 w-24 object-contain">
        </div>

        <!-- CONTENT -->
        <div class="flex-1">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
              <div class="mb-3 flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                  {{ meeting.title }}
                </span>

                <span v-if="meeting.opened"
                  class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                  ● Aktif
                </span>

                <span v-else class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                  ● Ditutup
                </span>
              </div>

              <h1 class="text-2xl font-bold text-slate-800">
                {{ meeting.subtitle }}
              </h1>

              <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-500">
                {{ meeting.description }}
              </p>
            </div>

          </div>

          <!-- PROGRESS -->
          <div class="mt-8">
            <div class="flex items-center justify-between">
              <div v-for="step in steps" :key="step.id" class="flex flex-1 items-center">
                <div class="flex flex-col items-center">
                  <!-- CIRCLE -->
                  <div :class="step.completed
                    ? 'bg-emerald-500 text-white'
                    : step.active
                      ? 'bg-blue-600 text-white'
                      : 'bg-slate-200 text-slate-500'
                    " class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold">
                    <CheckCircle2 v-if="step.completed" class="h-4 w-4" />

                    <span v-else>
                      {{ step.id }}
                    </span>
                  </div>

                  <span class="mt-2 text-xs font-medium text-slate-600">
                    {{ step.title }}
                  </span>
                </div>

                <!-- LINE -->
                <div v-if="
                  step.id !==
                  steps.length
                " class="mx-2 h-1 flex-1 rounded-full bg-slate-200">
                  <div v-if="
                    step.completed
                  " class="h-full w-full rounded-full bg-emerald-500" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- STEPS -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <h2 class="mb-6 text-lg font-bold text-slate-800">
        Tahapan Pembelajaran
      </h2>

      <div class="space-y-4">
        <div v-for="step in steps" :key="step.id"
          class="flex flex-col gap-4 rounded-2xl border border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
          <!-- LEFT -->
          <div class="flex items-start gap-4">
            <!-- NUMBER -->
            <div :class="step.completed
              ? 'bg-emerald-500 text-white'
              : step.active
                ? 'bg-blue-600 text-white'
                : 'bg-slate-200 text-slate-500'
              " class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold">
              {{ step.id }}
            </div>

            <!-- CONTENT -->
            <div>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                  <component :is="iconMap[step.icon]" class="h-5 w-5 text-slate-600" />
                </div>

                <div>
                  <h3 class="font-bold text-slate-800">
                    {{ step.title }}
                  </h3>

                  <p class="mt-1 text-sm text-slate-500">
                    {{
                      step.description
                    }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT -->
          <div class="flex flex-col items-start gap-3 lg:items-end">
            <!-- STATUS -->
            <div>
              <span v-if="
                step.completed
              " class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                ✓ Selesai
              </span>

              <span v-else-if="
                step.active
              " class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                ● Terbuka
              </span>

              <span v-else-if="
                step.teacher_only
              " class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                🔒 Terkunci
                (Akses Guru)
              </span>

              <span v-else class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                🔒 Terkunci
              </span>
            </div>

            <!-- BUTTON -->
            <a v-if="
              step.unlocked
            " :href="step.href"
              class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
              Buka
              {{ step.title }}
            </a>

            <button v-else disabled
              class="cursor-not-allowed rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-500">
              Tidak Tersedia
            </button>
          </div>
        </div>
      </div>

      <!-- NOTE -->
      <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
        📌 Catatan:
        Kamu harus menyelesaikan
        tahapan sebelumnya sebelum
        membuka tahap berikutnya.
      </div>

      <!-- FOOTER -->
      <div class="mt-8 flex items-center justify-between">
        <button
          class="flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
          <ChevronLeft class="h-4 w-4" />

          Kembali
        </button>

        <button
          class="flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
          Lanjut ke Materi

          <ChevronRight class="h-4 w-4" />
        </button>
      </div>
    </section>
  </div>
</template>