<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import axios from 'axios';

import { Save } from 'lucide-vue-next';

import { watch } from 'vue';

import { toast } from 'vue-sonner';

const props = defineProps<{
  meeting: any;
}>();

const form = useForm({
  meeting_id:
    props.meeting.id,

  question:
    props.meeting.evaluation?.question
    ?? '',
});

watch(
  () => props.meeting,
  (meeting) => {

    if (!meeting) {
      return;
    }

    form.meeting_id =
      meeting.id;

    form.question =
      meeting.evaluation?.question
      ?? '';
  },
);

const submit =
  async () => {

    try {

      const response =
        await axios.post(
          '/teacher/evaluations',
          form,
        );

      props.meeting.evaluation =
        response.data.evaluation;

      toast.success(
        'Evaluasi berhasil disimpan'
      );

    } catch (error) {

      toast.error(
        'Gagal menyimpan evaluasi'
      );
    }
  };

const toggleEvaluation =
  async () => {

    if (
      !props.meeting.evaluation
    ) {
      return;
    }

    try {

      const response =
        await axios.patch(
          `/teacher/evaluations/${props.meeting.evaluation.id}/toggle`
        );

      Object.assign(
        props.meeting.evaluation,
        response.data.evaluation,
      );

      toast.success(
        response.data.evaluation
          .is_active
          ? 'Evaluasi diaktifkan'
          : 'Evaluasi dinonaktifkan'
      );

    } catch (error) {

      toast.error(
        'Gagal mengubah status evaluasi'
      );
    }
  };
</script>

<template>
  <section class="rounded-xl bg-white p-4 shadow-sm">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

      <div>

        <h2 class="text-sm font-bold text-slate-800">

          📋 Evaluasi
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Atur pertanyaan evaluasi siswa.
        </p>
      </div>

      <!-- STATUS -->
      <button @click="toggleEvaluation" class="rounded-lg px-3 py-2 text-xs font-semibold transition" :class="meeting.evaluation?.is_active
          ? 'bg-emerald-500 text-white hover:bg-emerald-600'
          : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
        ">

        {{
          meeting.evaluation?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <!-- CONTENT -->
    <div class="mt-5 space-y-4">

      <!-- QUESTION -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Pertanyaan Evaluasi
        </label>

        <textarea v-model="form.question" rows="4" placeholder="Masukkan pertanyaan evaluasi..."
          class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-rose-500" />
      </div>

      <!-- BUTTON -->
      <div class="pt-1">

        <button @click="submit" :disabled="form.processing"
          class="inline-flex items-center gap-2 rounded-lg bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600 disabled:opacity-50">

          <Save class="h-4 w-4" />

          {{
            form.processing
              ? 'Menyimpan...'
              : 'Simpan Evaluasi'
          }}
        </button>
      </div>
    </div>
  </section>
</template>