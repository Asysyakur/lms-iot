<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Save } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { watch } from 'vue';

const props = defineProps<{
  meeting: any;
}>();

const form = useForm({
  meeting_id: props.meeting.id,

  question: props.meeting.evaluation?.question ?? '',
});

watch(
  () => props.meeting,
  (meeting) => {

    if (!meeting) {
      return;
    }

    form.meeting_id = meeting.id;

    form.question =
      meeting.evaluation?.question ?? '';
  },
);

const submit = async () => {

  try {

    const response =
      await axios.post(
        '/teacher/evaluations',
        form,
      );

    props.meeting.evaluation =
      response.data.evaluation;

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title:
        'Evaluasi berhasil disimpan',
      showConfirmButton: false,
      timer: 2500,
    });

  } catch (error) {

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title:
        'Gagal menyimpan evaluasi',
      showConfirmButton: false,
      timer: 2500,
    });
  }
};

const toggleEvaluation =
  async () => {

    if (!props.meeting.evaluation) {
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

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title:
          response.data.evaluation.is_active
            ? 'Evaluasi diaktifkan'
            : 'Evaluasi dinonaktifkan',
        showConfirmButton: false,
        timer: 2500,
      });

    } catch (error) {

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title:
          'Gagal mengubah status Evaluasi',
        showConfirmButton: false,
        timer: 2500,
      });
    }
  };
</script>

<template>
  <section class="max-w-full min-w-0 rounded-3xl bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-bold text-slate-800">
        📋 Evaluasi
      </h2>

      <button @click="toggleEvaluation" class="cursor-pointer rounded-xl px-4 py-2 text-sm font-semibold transition"
        :class="meeting.evaluation?.is_active
          ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white'
          : 'bg-slate-200 text-slate-700 hover:bg-slate-300 hover:text-slate-900'
          ">
        {{
          meeting.evaluation?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <div class="mt-6 space-y-5">
      <!-- QUESTION -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Pertanyaan Evaluasi
        </label>

        <textarea v-model="form.question" rows="5" placeholder="Masukkan pertanyaan evaluasi..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-rose-500" />
      </div>

      <!-- BUTTON -->
      <button @click="submit" :disabled="form.processing"
        class="cursor-pointer inline-flex items-center gap-2 rounded-2xl bg-rose-500 px-6 py-3 font-semibold text-white transition hover:bg-rose-600 disabled:opacity-50">
        <Save class="h-5 w-5" />

        {{
          form.processing
            ? 'Menyimpan...'
            : 'Simpan Evaluasi'
        }}
      </button>
    </div>
  </section>
</template>
