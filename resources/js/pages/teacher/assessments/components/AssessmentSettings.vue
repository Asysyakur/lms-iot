<!-- resources/js/pages/teacher/assessments/components/AssessmentSettings.vue -->

<script setup lang="ts">
import {
  CalendarDays,
  Clock3,
  ClipboardCheck,
} from 'lucide-vue-next';

defineProps<{
  type: 'pretest' | 'posttest';
}>();
</script>

<template>
  <div
    class="rounded-3xl bg-white p-6 shadow-sm"
  >
    <div
      class="mb-6 flex items-center gap-4"
    >
      <div
        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100"
      >
        <Clock3
          class="h-7 w-7 text-blue-600"
        />
      </div>

      <div>
        <h2
          class="text-xl font-bold text-slate-800"
        >
          Pengaturan Waktu
        </h2>

        <p
          class="mt-1 text-sm text-slate-500"
        >
          Atur jadwal dan durasi
          assessment.
        </p>
      </div>
    </div>

    <div class="space-y-5">
      <!-- DATE -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Tanggal Dibuka
        </label>

        <div class="relative">
          <CalendarDays
            class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
          />

          <input
            type="date"
            class="w-full rounded-2xl border border-slate-200 py-3 pl-12 pr-4 outline-none focus:border-blue-500"
          />
        </div>
      </div>

      <!-- TIME -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Jam Dibuka
        </label>

        <input
          type="time"
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500"
        />
      </div>

      <!-- DURATION -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Durasi
        </label>

        <select
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500"
        >
          <option>
            30 menit
          </option>

          <option>
            45 menit
          </option>

          <option>
            60 menit
          </option>
        </select>
      </div>

      <!-- ATTEMPT -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Kesempatan
        </label>

        <select
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500"
        >
          <option>
            1 kali pengerjaan
          </option>

          <option>
            2 kali pengerjaan
          </option>

          <option>
            3 kali pengerjaan
          </option>
        </select>
      </div>

      <!-- BUTTON -->
      <button
        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
      >
        <ClipboardCheck
          class="h-5 w-5"
        />

        Simpan Pengaturan
      </button>
    </div>
  </div>
</template>