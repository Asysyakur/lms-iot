<!-- resources/js/pages/teacher/assessments/components/AssessmentSettings.vue -->

<script setup lang="ts">
import { toast } from 'vue-sonner';

import { useForm } from '@inertiajs/vue3';

import {
  CalendarDays,
  Clock3,
  ClipboardCheck,
} from 'lucide-vue-next';

const props = defineProps<{
  type: 'pretest' | 'posttest';

  assessment?: any;
}>();

const form = useForm({
  type: props.type,

  open_date:
    props.assessment?.open_date ?? '',

  open_time:
    props.assessment?.open_time ?? '',

  duration:
    props.assessment?.duration ?? 30,

  attempts:
    props.assessment?.attempts ?? 1,
});

const submit = async () => {

  /**
   * VALIDASI DURATION
   */
  if (
    form.duration < 1 ||
    form.duration > 300
  ) {

    toast.error(
      'Durasi harus 1 - 300 menit'
    );

    return;
  }

  /**
   * VALIDASI ATTEMPTS
   */
  if (
    form.attempts < 1 ||
    form.attempts > 10
  ) {

    toast.error(
      'Kesempatan harus 1 - 10 kali'
    );

    return;
  }

  toast.promise(

    form.put(
      '/teacher/assessments/settings',
      {
        preserveScroll: true,
      }
    ),

    {
      loading:
        'Menyimpan pengaturan...',

      success:
        'Pengaturan berhasil disimpan',

      error:
        'Gagal menyimpan pengaturan',
    }
  );
};
</script>

<template>
  <div class="rounded-3xl bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center gap-4">
      <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">
        <Clock3 class="h-7 w-7 text-blue-600" />
      </div>

      <div>
        <h2 class="text-xl font-bold text-slate-800">
          Pengaturan Waktu
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Atur jadwal dan durasi
          assessment.
        </p>
      </div>
    </div>

    <div class="space-y-5">
      <!-- DATE -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Tanggal Dibuka
        </label>

        <div class="relative">
          <CalendarDays class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

          <input v-model="form.open_date" type="date"
            class="w-full rounded-2xl border border-slate-200 py-3 pl-12 pr-4 outline-none focus:border-blue-500" />
        </div>
      </div>

      <!-- TIME -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Jam Dibuka
        </label>

        <input v-model="form.open_time" type="time"
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500" />
      </div>

      <!-- DURATION -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Durasi Pengerjaan
        </label>

        <div class="relative">
          <input v-model="form.duration" type="number" min="1" max="300" placeholder="Contoh: 60"
            class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-20 outline-none focus:border-blue-500" />

          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-500">
            menit
          </span>
        </div>

        <p class="mt-2 text-xs text-slate-500">
          Maksimal 300 menit.
        </p>
      </div>

      <!-- ATTEMPT -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Kesempatan Pengerjaan
        </label>

        <div class="relative">
          <input v-model="form.attempts" type="number" min="1" max="10" placeholder="Contoh: 1"
            class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-24 outline-none focus:border-blue-500" />

          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-500">
            kali
          </span>
        </div>

        <p class="mt-2 text-xs text-slate-500">
          Maksimal 10 kali pengerjaan.
        </p>
      </div>

      <!-- BUTTON -->
      <button @click="submit" type="button" :disabled="form.processing"
        class="cursor-pointer inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
        <ClipboardCheck class="h-5 w-5" />

        Simpan Pengaturan
      </button>
    </div>
  </div>
</template>