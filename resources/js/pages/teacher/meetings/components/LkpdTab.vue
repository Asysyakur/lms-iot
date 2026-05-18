<!-- resources/js/pages/teacher/meetings/components/LkpdTab.vue -->

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Upload, Save } from 'lucide-vue-next';
import { watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps<{
  meeting: any;
}>();

const form = useForm({
  meeting_id: props.meeting.id,

  google_docs_url: props.meeting.lkpd?.google_docs_url ?? '',

  submission_note: props.meeting.lkpd?.submission_note ?? '',
});

watch(
  () => props.meeting,
  (meeting) => {

    form.meeting_id = meeting.id;

    form.google_docs_url =
      meeting.lkpd?.google_docs_url ?? '';

    form.submission_note =
      meeting.lkpd?.submission_note ?? '';
  },
  {
    immediate: true,
  },
);

const submit = async () => {

  try {

    const response =
      await axios.post(
        '/teacher/lkpds',
        form,
      );

    props.meeting.lkpd =
      response.data.lkpd;

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title:
        'LKPD berhasil disimpan',
      showConfirmButton: false,
      timer: 2500,
    });

  } catch (error) {

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title:
        'Gagal menyimpan LKPD',
      showConfirmButton: false,
      timer: 2500,
    });
  }
};

const toggleLkpd =
  async () => {

    if (!props.meeting.lkpd) {
      return;
    }

    try {

      const response =
        await axios.patch(
          `/teacher/lkpds/${props.meeting.lkpd.id}/toggle`
        );

      Object.assign(
        props.meeting.lkpd,
        response.data.lkpd,
      );

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title:
          response.data.lkpd.is_active
            ? 'LKPD diaktifkan'
            : 'LKPD dinonaktifkan',
        showConfirmButton: false,
        timer: 2500,
      });

    } catch (error) {

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title:
          'Gagal mengubah status LKPD',
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
        📄 Pengaturan LKPD
      </h2>

      <button @click="toggleLkpd" class="cursor-pointer rounded-xl px-4 py-2 text-sm font-semibold transition" :class="meeting.lkpd?.is_active
        ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white'
        : 'bg-slate-200 text-slate-700 hover:bg-slate-300 hover:text-slate-900'
        ">
        {{
          meeting.lkpd?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>


    <div class="mt-6 space-y-5">
      <!-- FILE -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Upload File LKPD
        </label>

        <input type="file"
          class="w-full rounded-2xl border border-slate-200 p-3 outline-none transition focus:border-orange-500" />
      </div>

      <!-- GOOGLE DOCS -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Link Google Docs Template
        </label>

        <input v-model="form.google_docs_url" type="text" placeholder="https://docs.google.com/..."
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-orange-500" />
      </div>

      <!-- NOTE -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Catatan Pengumpulan
        </label>

        <textarea v-model="form.submission_note" rows="5" placeholder="Masukkan catatan pengumpulan LKPD..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-orange-500" />
      </div>

      <!-- BUTTON -->
      <button @click="submit" :disabled="form.processing"
        class="cursor-pointer inline-flex items-center gap-2 rounded-2xl bg-orange-500 px-6 py-3 font-semibold text-white transition hover:bg-orange-600 disabled:opacity-50">
        <Upload class="h-5 w-5" />

        {{
          form.processing
            ? 'Menyimpan...'
            : 'Simpan LKPD'
        }}
      </button>
    </div>
  </section>
</template>
