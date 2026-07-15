```vue
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
  classOptions: string[];
  selectedClass: string;
}>();

const form = useForm({
  type: props.type,
  target_class: props.selectedClass || '',

  open_date:
    props.assessment?.open_date ?? '',

  open_time:
    props.assessment?.open_time ?? '',

  duration:
    props.assessment?.duration ?? 30,

  attempts:
    props.assessment?.attempts ?? 1,
});

const navigateClass = () => {
  window.location.href =
    `/teacher/assessments/${props.type}?class=${encodeURIComponent(form.target_class)}`;
};

const submit = () => {

  if (
    form.duration < 1 ||
    form.duration > 300
  ) {

    toast.error(
      'Durasi harus 1 - 300 menit'
    );

    return;
  }

  if (
    form.attempts < 1 ||
    form.attempts > 10
  ) {

    toast.error(
      'Kesempatan harus 1 - 10 kali'
    );

    return;
  }

  form.put(
    '/teacher/assessments/settings',
    {
      preserveScroll: true,

      onStart: () => {

        toast.loading(
          'Menyimpan pengaturan...',
          {
            id: 'save-settings',
          }
        );
      },

      onSuccess: () => {

        toast.success(
          'Pengaturan berhasil disimpan',
          {
            id: 'save-settings',
          }
        );
      },

      onError: () => {

        toast.error(
          'Gagal menyimpan pengaturan',
          {
            id: 'save-settings',
          }
        );
      },
    }
  );
};

</script>

<template>
  <div class="rounded-xl bg-white p-4 shadow-sm">

    <!-- HEADER -->
    <div class="mb-4 flex items-center gap-3">

      <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">

        <Clock3 class="h-5 w-5 text-blue-600" />
      </div>

      <div>

        <h2 class="text-sm font-bold text-slate-800">

          Pengaturan Waktu
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Atur jadwal dan durasi
          assessment.
        </p>
      </div>
    </div>

    <!-- CLASS -->
    <div class="mb-4">
      <label class="mb-2 block text-xs font-semibold text-slate-700">
        Kelas Target
      </label>

      <select v-model="form.target_class" @change="navigateClass"
        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500">
        <option value="">
          Semua kelas
        </option>

        <option v-for="item in classOptions" :key="item" :value="item">
          {{ item }}
        </option>
      </select>
    </div>

    <!-- CONTENT -->
    <div class="space-y-4">

      <!-- DATE -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Tanggal Dibuka
        </label>

        <div class="relative">

          <CalendarDays class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />

          <input v-model="form.open_date" type="date"
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500" />
        </div>
      </div>

      <!-- TIME -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Jam Dibuka
        </label>

        <input v-model="form.open_time" type="time"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500" />
      </div>

      <!-- DURATION -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Durasi Pengerjaan
        </label>

        <div class="relative">

          <input v-model="form.duration" type="number" min="1" max="300" placeholder="Contoh: 60"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 pr-16 text-sm outline-none transition focus:border-blue-500" />

          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[11px] font-medium text-slate-500">

            menit
          </span>
        </div>
      </div>

      <!-- ATTEMPTS -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Kesempatan Pengerjaan
        </label>

        <div class="relative">

          <input v-model="form.attempts" type="number" min="1" max="10" placeholder="Contoh: 1"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 pr-16 text-sm outline-none transition focus:border-blue-500" />

          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[11px] font-medium text-slate-500">

            kali
          </span>
        </div>
      </div>

      <!-- BUTTON -->
      <button @click="submit" type="button" :disabled="form.processing"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">

        <ClipboardCheck class="h-4 w-4" />

        {{
          form.processing
            ? 'Menyimpan...'
            : 'Simpan Pengaturan'
        }}
      </button>
    </div>
  </div>
</template>
```
