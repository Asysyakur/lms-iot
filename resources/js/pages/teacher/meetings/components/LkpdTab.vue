<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import {
  Upload,
} from 'lucide-vue-next';

import { watch } from 'vue';

import axios from 'axios';

import { toast } from 'vue-sonner';

const props = defineProps<{
  meeting: any;
}>();

const form = useForm({
  meeting_id:
    props.meeting.id,

  google_docs_url:
    props.meeting.lkpd
      ?.google_docs_url ?? '',

  submission_note:
    props.meeting.lkpd
      ?.submission_note ?? '',
});

watch(
  () => props.meeting,
  (meeting) => {

    form.meeting_id =
      meeting.id;

    form.google_docs_url =
      meeting.lkpd
        ?.google_docs_url ?? '';

    form.submission_note =
      meeting.lkpd
        ?.submission_note ?? '';
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
          '/teacher/lkpds',
          form,
        );

      props.meeting.lkpd =
        response.data.lkpd;

      toast.success(
        'LKPD berhasil disimpan'
      );

    } catch (error) {

      toast.error(
        'Gagal menyimpan LKPD'
      );
    }
  };

const toggleLkpd =
  async () => {

    if (
      !props.meeting.lkpd
    ) {
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

      toast.success(
        response.data.lkpd
          .is_active
          ? 'LKPD diaktifkan'
          : 'LKPD dinonaktifkan'
      );

    } catch (error) {

      toast.error(
        'Gagal mengubah status LKPD'
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

          Pengaturan LKPD
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Atur template dan pengumpulan
          LKPD.
        </p>
      </div>

      <!-- STATUS -->
      <button @click="toggleLkpd" class="rounded-lg px-3 py-2 text-xs font-semibold transition" :class="meeting.lkpd?.is_active
          ? 'bg-emerald-500 text-white hover:bg-emerald-600'
          : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
        ">

        {{
          meeting.lkpd?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <!-- CONTENT -->
    <div class="mt-5 space-y-4">

      <!-- FILE -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Upload File LKPD
        </label>

        <input type="file"
          class="w-full rounded-lg border border-slate-200 p-2.5 text-sm outline-none transition focus:border-orange-500" />
      </div>

      <!-- GOOGLE DOCS -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Link Google Docs
        </label>

        <input v-model="form.google_docs_url" type="text" placeholder="https://docs.google.com/..."
          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-orange-500" />
      </div>

      <!-- NOTE -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Catatan Pengumpulan
        </label>

        <textarea v-model="form.submission_note" rows="4" placeholder="Masukkan catatan pengumpulan..."
          class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-500" />
      </div>

      <!-- BUTTON -->
      <div class="pt-1">

        <button @click="submit" :disabled="form.processing"
          class="inline-flex items-center gap-2 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600 disabled:opacity-50">

          <Upload class="h-4 w-4" />

          {{
            form.processing
              ? 'Menyimpan...'
              : 'Simpan LKPD'
          }}
        </button>
      </div>
    </div>
  </section>
</template>