<!-- resources/js/pages/teacher/meetings/components/PracticeTab.vue -->

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import {
  Save,
} from 'lucide-vue-next';

import {
  watch,
} from 'vue';

import axios from 'axios';

import { toast } from 'vue-sonner';

const props = defineProps<{
  meeting: any;
}>();

const form = useForm({
  meeting_id:
    props.meeting.id,

  instruction:
    props.meeting.practice
      ?.instruction ?? '',

  makecode_url:
    props.meeting.practice
      ?.makecode_url ?? '',

  submission_type:
    props.meeting.practice
      ?.submission_type ?? 'link',
});

watch(
  () => props.meeting,
  (meeting) => {

    form.meeting_id =
      meeting.id;

    form.instruction =
      meeting.practice
        ?.instruction ?? '';

    form.makecode_url =
      meeting.practice
        ?.makecode_url ?? '';

    form.submission_type =
      meeting.practice
        ?.submission_type ?? 'link';
  },
  {
    immediate: true,
  },
);

const submit =
  async () => {

    try {

      const response =
        await axios.post(
          '/teacher/practices',
          form,
        );

      props.meeting.practice =
        response.data.practice;

      toast.success(
        'Praktik berhasil disimpan'
      );

    } catch (error) {

      toast.error(
        'Gagal menyimpan praktik'
      );
    }
  };

const togglePractice =
  async () => {

    if (
      !props.meeting.practice
    ) {
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

      toast.success(
        response.data.practice
          .is_active
          ? 'Praktik diaktifkan'
          : 'Praktik dinonaktifkan'
      );

    } catch (error) {

      toast.error(
        'Gagal mengubah status praktik'
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

          Pengaturan Praktik
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Atur instruksi dan link MakeCode.
        </p>
      </div>

      <!-- STATUS -->
      <button @click="togglePractice" class="rounded-lg px-3 py-2 text-xs font-semibold transition" :class="meeting.practice?.is_active
        ? 'bg-emerald-500 text-white hover:bg-emerald-600'
        : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
        ">

        {{
          meeting.practice?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <!-- CONTENT -->
    <div class="mt-5 space-y-4">

      <!-- INSTRUCTION -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Instruksi Praktik
        </label>

        <textarea v-model="form.instruction" rows="6" placeholder="Masukkan instruksi praktik..."
          class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-purple-500" />
      </div>

      <!-- SUBMISSION TYPE -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Tipe Pengumpulan

        </label>

        <select v-model="form.submission_type"
          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-purple-500">

          <option value="link">
            Link MakeCode
          </option>

          <option value="text">
            Jawaban Teks
          </option>

          <option value="both">
            Link + Jawaban Teks
          </option>

        </select>

      </div>

      <!-- MAKECODE -->
      <div v-if="
        form.submission_type !==
        'text'
      ">

        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Link MakeCode
        </label>

        <input v-model="form.makecode_url" type="text" placeholder="https://makecode.microbit.org/..."
          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-purple-500" />

      </div>

      <!-- BUTTON -->
      <div class="pt-1">

        <button @click="submit" :disabled="form.processing"
          class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:opacity-50">

          <Save class="h-4 w-4" />

          {{
            form.processing
              ? 'Menyimpan...'
              : 'Simpan Praktik'
          }}
        </button>
      </div>
    </div>
  </section>
</template>