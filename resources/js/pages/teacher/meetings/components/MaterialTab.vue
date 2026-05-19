<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { reactive, watch } from 'vue';
import axios from 'axios';

const props = defineProps<{
  meeting: any;
}>();


const form = useForm({
  meeting_id: props.meeting.id,

  title: props.meeting?.material?.title ?? '',

  description:
    props.meeting?.material?.description ?? '',

  video_url:
    props.meeting?.material?.video_url ?? '',

  trigger_question:
    props.meeting?.material?.trigger_question ?? '',

  reflection_question:
    props.meeting?.material?.reflection_question ?? [''],

  pdf_file: null,
});

/**
 * AUTO UPDATE FORM
 * SAAT MEETING BERUBAH
 */
watch(
  () => props.meeting,
  (meeting) => {

    if (!meeting) {
      return;
    }

    Object.assign(
      meeting.material ?? {},
    );

    form.meeting_id = meeting.id;

    form.title =
      props.meeting?.material?.title ?? '';

    form.description =
      props.meeting?.material?.description ?? '';

    form.video_url =
      props.meeting?.material?.video_url ?? '';

    form.trigger_question =
      props.meeting?.material?.trigger_question ?? '';

    form.reflection_question =
      props.meeting?.material?.reflection_question ?? [''];

    form.pdf_file = null;
  },
  {
    immediate: true,
  },
);

const submit = async () => {

  try {

    const formData = new FormData();

    formData.append(
      'meeting_id',
      String(form.meeting_id),
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

    form.reflection_question.forEach(
      (question, index) => {

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

    Object.assign(
      props.meeting.material,
      response.data.material,
    );

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Materi berhasil disimpan',
      showConfirmButton: false,
      timer: 2500,
    });

  } catch (error) {

    console.error(error);

    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: 'Gagal menyimpan materi',
      showConfirmButton: false,
      timer: 2500,
    });

  }

};

const toggleMaterial =
  async () => {

    if (!props.meeting.material) {
      return;
    }

    try {

      const response =
        await axios.patch(
          `/teacher/materials/${props.meeting.material.id}/toggle`
        );

      Object.assign(
        props.meeting.material,
        response.data.material,
      );

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title:
          response.data.material.is_active
            ? 'Materi diaktifkan'
            : 'Materi dinonaktifkan',
        showConfirmButton: false,
        timer: 2500,
      });

    } catch (error) {

      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title:
          'Gagal mengubah status materi',
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
        📘 Pengaturan Materi
      </h2>
      <button @click="toggleMaterial" class="cursor-pointer rounded-xl px-4 py-2 text-sm font-semibold transition"
        :class="props.meeting?.material?.is_active
          ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white'
          : 'bg-slate-200 text-slate-700 hover:bg-slate-300 hover:text-slate-900'">
        {{
          props.meeting?.material?.is_active
            ? 'Aktif'
            : 'Draft'
        }}
      </button>
    </div>

    <div class="mt-6 space-y-5">
      <!-- TITLE -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Judul Materi
        </label>

        <input v-model="form.title" type="text" placeholder="Masukkan judul materi"
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-blue-500" />
      </div>

      <!-- DESCRIPTION -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Deskripsi Materi
        </label>

        <textarea v-model="form.description" rows="5" placeholder="Masukkan deskripsi materi..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500" />
      </div>

      <!-- FILE + VIDEO -->
      <div class="grid gap-5 md:grid-cols-2">
        <!-- PDF -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Upload PDF Materi
          </label>

          <input type="file" accept=".pdf" @input="
            form.pdf_file = $event.target.files[0]
            " class="w-full rounded-2xl border border-slate-200 p-3" />
        </div>

        <!-- VIDEO -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Link Video Youtube
          </label>

          <input v-model="form.video_url" type="text" placeholder="https://youtube.com/..."
            class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-blue-500" />
        </div>
      </div>

      <!-- PEMANTIK -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Pertanyaan Pemantik
        </label>

        <textarea v-model="form.trigger_question" rows="4" placeholder="Masukkan pertanyaan pemantik..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500" />
      </div>

      <!-- REFLEKSI -->
      <div>
        <div class="mb-3 flex items-center justify-between">
          <label class="block text-sm font-semibold text-slate-700">
            Pertanyaan Refleksi
          </label>

          <button type="button" @click="form.reflection_question.push('')"
            class="rounded-xl bg-blue-100 px-3 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-200">
            + Tambah Pertanyaan
          </button>
        </div>

        <div v-for="(question, index) in form.reflection_question" :key="index" class="mb-4">
          <div class="mb-2 flex items-center justify-between">
            <span class="text-sm font-semibold text-slate-600">
              Pertanyaan {{ index + 1 }}
            </span>

            <button v-if="form.reflection_question.length > 1" type="button"
              @click="form.reflection_question.splice(index, 1)"
              class="text-sm font-semibold text-red-500 hover:text-red-700">
              Hapus
            </button>
          </div>

          <textarea v-model="form.reflection_question[index]" rows="3" placeholder="Masukkan pertanyaan refleksi..."
            class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500" />
        </div>
      </div>

      <!-- BUTTON -->
      <button @click="submit" :disabled="form.processing"
        class="cursor-pointer inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:opacity-50">
        <Save class="h-5 w-5" />

        {{
          form.processing
            ? 'Menyimpan...'
            : 'Simpan Materi'
        }}
      </button>
    </div>
  </section>
</template>
