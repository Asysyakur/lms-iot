<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Save } from 'lucide-vue-next';

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

  title:
    props.meeting?.material
      ?.title ?? '',

  description:
    props.meeting?.material
      ?.description ?? '',

  video_url:
    props.meeting?.material
      ?.video_url ?? '',

  trigger_question:
    props.meeting?.material
      ?.trigger_question ?? '',

  reflection_question:
    props.meeting?.material
      ?.reflection_question
    ?? [''],

  pdf_file: null,
});

/**
 * AUTO UPDATE FORM
 */
watch(
  () => props.meeting,
  (meeting) => {

    if (!meeting) {
      return;
    }

    form.meeting_id =
      meeting.id;

    form.title =
      meeting?.material
        ?.title ?? '';

    form.description =
      meeting?.material
        ?.description ?? '';

    form.video_url =
      meeting?.material
        ?.video_url ?? '';

    form.trigger_question =
      meeting?.material
        ?.trigger_question ?? '';

    form.reflection_question =
      meeting?.material
        ?.reflection_question
      ?? [''];

    form.pdf_file = null;
  },
  {
    immediate: true,
  },
);

const existingPdfUrl =
  computed(() => {

    return props.meeting
      ?.material
      ?.pdf_url
      ?? null;

  });

const existingPdfName =
  computed(() => {

    if (form.pdf_file) {

      return form.pdf_file.name;

    }

    const path =
      props.meeting
        ?.material
        ?.pdf_file;

    if (!path) {
      return null;
    }

    return path
      .split('/')
      .pop();

  });

const submit =
  async () => {

    try {

      const formData =
        new FormData();

      formData.append(
        'meeting_id',
        String(
          form.meeting_id
        ),
      );

      formData.append(
        'title',
        form.title ?? '',
      );

      formData.append(
        'description',
        form.description ?? '',
      );

      formData.append(
        'video_url',
        form.video_url ?? '',
      );

      formData.append(
        'trigger_question',
        form.trigger_question ?? '',
      );

      form.reflection_question
        .forEach(
          (
            question,
            index
          ) => {

            formData.append(
              `reflection_question[${index}]`,
              question
            );

          }
        );

      if (form.pdf_file) {

        formData.append(
          'pdf_file',
          form.pdf_file,
        );

      }

      const response =
        await axios.post(
          '/teacher/materials',
          formData,
        );

      props.meeting.material =
        response.data.material;

      toast.success(
        'Materi berhasil disimpan'
      );

    } catch (error) {

      console.error(error);

      toast.error(
        'Gagal menyimpan materi'
      );
    }
  };

const toggleMaterial =
  async () => {

    if (
      !props.meeting.material
    ) {
      return;
    }

    try {

      const response =
        await axios.patch(
          `/teacher/materials/${props.meeting.material.id}/toggle`
        );

      props.meeting.material =
        response.data.material;

      toast.success(
        response.data.material
          .is_active
          ? 'Materi diaktifkan'
          : 'Materi dinonaktifkan'
      );

    } catch (error) {

      toast.error(
        'Gagal mengubah status materi'
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

          Pengaturan Materi
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Kelola materi pembelajaran.
        </p>
      </div>

      <!-- STATUS -->
      <button @click="toggleMaterial" class="rounded-lg px-3 py-2 text-xs font-semibold transition" :class="props.meeting?.material?.is_active
        ? 'bg-emerald-500 text-white hover:bg-emerald-600'
        : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
        ">

        {{
          props.meeting?.material?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <!-- CONTENT -->
    <div class="mt-5 space-y-4">

      <!-- TITLE -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Judul Materi
        </label>

        <input v-model="form.title" type="text" placeholder="Masukkan judul materi"
          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500" />
      </div>

      <!-- DESCRIPTION -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Deskripsi Materi
        </label>

        <textarea v-model="form.description" rows="4" placeholder="Masukkan deskripsi materi..."
          class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500" />
      </div>

      <!-- PDF + VIDEO -->
      <div class="grid gap-4 lg:grid-cols-2">

        <!-- PDF -->
        <div>

          <label class="mb-2 block text-sm font-semibold text-slate-700">

            Upload PDF
          </label>

          <input type="file" accept=".pdf" @input="
            form.pdf_file =
            $event.target.files[0]
            " class="w-full rounded-lg border border-slate-200 p-2.5 text-sm" />
          <div v-if="existingPdfName" class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">

            <div class="flex items-center justify-between gap-3">

              <div>

                <p class="text-sm font-semibold text-slate-700">
                  PDF tersedia
                </p>

                <p class="mt-1 break-all text-xs text-slate-500">
                  {{ existingPdfName }}
                </p>
              </div>

              <a v-if="existingPdfUrl" :href="existingPdfUrl" target="_blank"
                class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                Buka PDF
              </a>
            </div>
          </div>
        </div>

        <!-- VIDEO -->
        <div>

          <label class="mb-2 block text-sm font-semibold text-slate-700">

            Link Youtube
          </label>

          <input v-model="form.video_url" type="text" placeholder="https://youtube.com/..."
            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500" />
        </div>
      </div>

      <!-- PEMANTIK -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Pertanyaan Pemantik
        </label>

        <textarea v-model="form.trigger_question" rows="3" placeholder="Masukkan pertanyaan pemantik..."
          class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500" />
      </div>

      <!-- REFLEKSI -->
      <div>

        <!-- TOP -->
        <div class="mb-3 flex items-center justify-between">

          <label class="text-sm font-semibold text-slate-700">

            Pertanyaan Refleksi
          </label>

          <button type="button" @click="
            form.reflection_question.push('')
            "
            class="rounded-lg bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-200">

            + Tambah
          </button>
        </div>

        <!-- LIST -->
        <div class="space-y-3">

          <div v-for="(question, index) in form.reflection_question" :key="index"
            class="rounded-xl border border-slate-200 p-3">

            <!-- HEADER -->
            <div class="mb-2 flex items-center justify-between">

              <span class="text-xs font-semibold text-slate-600">

                Pertanyaan
                {{ index + 1 }}
              </span>

              <button v-if="form.reflection_question.length > 1" type="button" @click="
                form.reflection_question.splice(index, 1)
                " class="text-xs font-semibold text-red-500 hover:text-red-700">

                Hapus
              </button>
            </div>

            <!-- INPUT -->
            <textarea v-model="form.reflection_question[index]
              " rows="3" placeholder="Masukkan pertanyaan refleksi..."
              class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500" />
          </div>
        </div>
      </div>

      <!-- BUTTON -->
      <div class="pt-2">

        <button @click="submit" :disabled="form.processing"
          class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-50">

          <Save class="h-4 w-4" />

          {{
            form.processing
              ? 'Menyimpan...'
              : 'Simpan Materi'
          }}
        </button>
      </div>
    </div>
  </section>
</template>