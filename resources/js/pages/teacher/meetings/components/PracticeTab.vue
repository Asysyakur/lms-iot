<!-- resources/js/pages/teacher/meetings/components/PracticeTab.vue -->

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import { watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps<{
  meeting: any;
}>();

const form = useForm({
  meeting_id: props.meeting.id,

  instruction: props.meeting.practice?.instruction ?? '',

  makecode_url: props.meeting.practice?.makecode_url ?? '',
});

watch(
  () => props.meeting,
  (meeting) => {

    form.meeting_id = meeting.id;

    form.instruction =
      meeting.practice?.instruction ?? '';

    form.makecode_url =
      meeting.practice?.makecode_url ?? '';
  },
  {
    immediate: true,
  },
);

const submit = async () => {

  try {

    const response =
      await axios.post(
        '/teacher/practices',
        form,
      );

    props.meeting.practice =
      response.data.practice;

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title:
        'Praktik berhasil disimpan',
      showConfirmButton: false,
      timer: 2500,
    });

  } catch (error) {

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title:
        'Gagal menyimpan praktik',
      showConfirmButton: false,
      timer: 2500,
    });
  }
};

const togglePractice =
  async () => {

    if (!props.meeting.practice) {
      return;
    }

    try {

      const response =
        await axios.patch(
          `/teacher/practices/${props.meeting.practice.id}/toggle`
        );

      Object.assign(
        props.meeting.practice,
        response.data.practice,
      );

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title:
          response.data.practice.is_active
            ? 'Praktik diaktifkan'
            : 'Praktik dinonaktifkan',
        showConfirmButton: false,
        timer: 2500,
      });

    } catch (error) {

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title:
          'Gagal mengubah status praktik',
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
        🧪 Pengaturan Praktik
      </h2>

      <button @click="togglePractice" class="cursor-pointer rounded-xl px-4 py-2 text-sm font-semibold transition"
        :class="meeting.practice?.is_active
          ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white'
          : 'bg-slate-200 text-slate-700 hover:bg-slate-300 hover:text-slate-900'
          ">
        {{
          meeting.practice?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <div class="mt-6 space-y-5">
      <!-- INSTRUCTION -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Instruksi Praktik
        </label>

        <textarea v-model="form.instruction" rows="8" placeholder="Masukkan instruksi praktik..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-purple-500" />
      </div>

      <!-- MAKECODE URL -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Link MakeCode
        </label>

        <input v-model="form.makecode_url" type="text" placeholder="https://makecode.microbit.org/..."
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-purple-500" />
      </div>

      <!-- BUTTON -->
      <button @click="submit" :disabled="form.processing"
        class="cursor-pointer inline-flex items-center gap-2 rounded-2xl bg-purple-500 px-6 py-3 font-semibold text-white transition hover:bg-purple-600 disabled:opacity-50">
        <Save class="h-5 w-5" />

        {{
          form.processing
            ? 'Menyimpan...'
            : 'Simpan Praktik'
        }}
      </button>
    </div>
  </section>
</template>