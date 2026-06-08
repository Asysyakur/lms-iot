<!-- resources/js/pages/student/meetings/lkpd/Show.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';
import axios from 'axios';
import { toast } from 'vue-sonner';
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
  FileSpreadsheet,
  CheckCircle2,
  ExternalLink,
  Upload,
  Lock,
  FileText,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  meeting: any;
  lkpd: any;
  submission: any;
}>();

const selectedFile = ref<File | null>(
  null
);

const submitted = ref(false);

const currentSubmission = ref(
  props.submission
);

const instructions = [
  'Baca seluruh petunjuk pengerjaan LKPD terlebih dahulu.',
  'Buat salinan template Google Docs sebelum mulai mengerjakan.',
  'Isi seluruh jawaban sesuai hasil praktik yang telah dilakukan.',
  'Pastikan link Google Docs dapat diakses guru.',
];

const allowedExtensions = [
  'pdf',
  'doc',
  'docx',
];

const templateFileUrl =
  computed(() => {

    if (
      !props.lkpd?.template_file
    ) {
      return null;
    }

    return `/storage/${props.lkpd.template_file}`;
  });

const validateFile = (
  file: File
) => {

  const extension =
    file.name
      .split('.')
      .pop()
      ?.toLowerCase();

  if (
    !extension ||
    !allowedExtensions.includes(
      extension
    )
  ) {

    toast.error(
      'File harus PDF atau Word'
    );

    return false;
  }

  return true;
};

const submissionNote =
  computed(() => {

    return (
      props.lkpd
        ?.submission_note
      ||
      null
    );
  });

const canSubmit = computed(() => {
  return !!selectedFile.value;
});

const isDragging = ref(false);

const handleDrop = (
  event: DragEvent
) => {

  event.preventDefault();

  isDragging.value = false;

  if (
    !event.dataTransfer?.files.length
  ) {
    return;
  }

  const file =
    event.dataTransfer.files[0];

  if (
    !validateFile(file)
  ) {
    return;
  }

  selectedFile.value = file;
};

const handleDragOver = (
  event: DragEvent
) => {

  event.preventDefault();

  isDragging.value = true;
};

const handleDragLeave = () => {

  isDragging.value = false;
};

const handleFileChange = (
  event: Event
) => {

  const target =
    event.target as HTMLInputElement;

  if (!target.files?.length) {
    return;
  }

  const file =
    target.files[0];

  if (
    !validateFile(file)
  ) {
    return;
  }

  selectedFile.value = file;
};

const evalOpened = computed(() => {
  return props.meeting.evaluation.is_active;
});

const existingFile = computed(() => {
  return currentSubmission.value?.file_path;
});

const existingFileName = computed(() => {

  if (!existingFile.value) {
    return null;
  }

  return existingFile.value
    .split('/')
    .pop();
});

const submitLkpd = async () => {

  if (!selectedFile.value) {
    return;
  }

  const formData =
    new FormData();

  formData.append(
    'file',
    selectedFile.value
  );

  const request =
    axios.post(
      `/student/meetings/${props.meeting.id}/lkpd/submit`,
      formData,
      {
        headers: {
          'Content-Type':
            'multipart/form-data',
        },
      }
    );

  toast.promise(request, {

    loading:
      'Mengupload LKPD...',

    success: () => {

      submitted.value = true;

      currentSubmission.value = {
        file_path:
          'uploaded',
      };

      return 'LKPD berhasil dikumpulkan';
    },

    error: (error: any) => {

      return (
        error.response?.data?.message
        ||
        'Upload gagal'
      );
    },
  });

  await request;
};
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-orange-600 to-amber-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <span class="rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-semibold">

          Pertemuan
          {{ props.meeting.meeting_number }}
        </span>

        <h1 class="mt-3 text-2xl font-bold">

          LKPD
          {{ props.meeting.title }}
        </h1>

        <p class="mt-1 max-w-2xl text-sm text-orange-100">

          Kerjakan LKPD berdasarkan hasil
          praktik lalu upload file
          pengerjaanmu.
        </p>
      </div>
    </section>

    <!-- FLOW -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <div class="flex items-center">

        <!-- MATERI -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Materi
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-emerald-500" />

        <!-- REFLEKSI -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Refleksi
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-emerald-500" />

        <!-- KUIS -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Kuis
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-emerald-500" />

        <!-- PRAKTIK -->
        <div class="flex flex-col items-center">

          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">

            ✓
          </div>

          <p class="mt-2 text-[10px] font-semibold text-emerald-600">

            Praktik
          </p>
        </div>

        <div class="mx-2 mb-5 h-[3px] flex-1 rounded-full bg-orange-500" />

        <!-- LKPD -->
        <div class="flex flex-col items-center">

          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">

            5
          </div>

          <p class="mt-2 text-[10px] font-semibold text-orange-600">

            LKPD
          </p>
        </div>
      </div>
    </section>

    <!-- INSTRUCTIONS -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <div class="mb-4 flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100">

          <FileSpreadsheet class="h-5 w-5 text-orange-600" />
        </div>

        <div>

          <h2 class="text-sm font-bold text-slate-800">

            Instruksi LKPD
          </h2>

          <p class="mt-1 text-xs text-slate-500">

            Ikuti petunjuk berikut.
          </p>
        </div>
      </div>

      <div class="space-y-3">

        <div v-for="(instruction, index) in instructions" :key="instruction"
          class="flex items-start gap-3 rounded-xl border border-slate-200 p-3">

          <div
            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-orange-500 text-[11px] font-bold text-white">

            {{ index + 1 }}
          </div>

          <p class="pt-1 text-sm leading-relaxed text-slate-700">

            {{ instruction }}
          </p>
        </div>
      </div>
    </section>

    <!-- TEMPLATE -->
    <!-- <section class="rounded-xl border border-blue-200 bg-blue-50 p-4">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex items-start gap-3">

          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white">

            <FileText class="h-5 w-5 text-blue-600" />
          </div>

          <div>

            <h2 class="text-sm font-bold text-blue-700">

              Template LKPD
            </h2>

            <p class="mt-1 text-xs text-blue-600">

              Buat salinan template sebelum
              mengerjakan.
            </p>
          </div>
        </div>

        <a v-if="templateLink" :href="templateLink" target="_blank"
          class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">

          <ExternalLink class="h-4 w-4" />

          Buka Template
        </a>
      </div>
    </section> -->
    <!-- FILE TEMPLATE -->
    <section v-if="templateFileUrl" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex items-start gap-3">

          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white">

            <FileText class="h-5 w-5 text-emerald-600" />
          </div>

          <div>

            <h2 class="text-sm font-bold text-emerald-700">

              Template LKPD
            </h2>

            <p class="mt-1 text-xs text-emerald-600">

              Download template LKPD dari guru.
            </p>
          </div>
        </div>

        <a :href="templateFileUrl" target="_blank"
          class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">

          <ExternalLink class="h-4 w-4" />

          Download Template
        </a>
      </div>
    </section>
    <!-- NOTE -->
    <section v-if="submissionNote" class="rounded-xl border border-orange-200 bg-orange-50 p-4">

      <h2 class="text-sm font-bold text-orange-700">

        Catatan Pengumpulan
      </h2>

      <p class="mt-2 text-sm leading-relaxed text-orange-700">

        {{ submissionNote }}
      </p>
    </section>

    <!-- SUBMIT -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <div class="mb-4 flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">

          <Upload class="h-5 w-5 text-emerald-600" />
        </div>

        <div>

          <h2 class="text-sm font-bold text-slate-800">

            Upload LKPD
          </h2>

          <p class="mt-1 text-xs text-slate-500">

            Upload file hasil pengerjaanmu.
          </p>
        </div>
      </div>

      <div class="space-y-4">

        <!-- DROPZONE -->
        <div @drop="handleDrop" @dragover="handleDragOver" @dragleave="handleDragLeave" :class="isDragging
          ? 'border-orange-500 bg-orange-100'
          : 'border-orange-300 bg-orange-50'
          "
          class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed px-5 py-10 text-center transition">

          <Upload class="mb-3 h-9 w-9 text-orange-500" />

          <h3 class="text-sm font-bold text-slate-700">

            Klik atau Seret File
          </h3>

          <p class="mt-1 text-xs text-slate-500">

            PDF, DOC, DOCX
            (maks 10 MB)
          </p>

          <input ref="fileInput" type="file" class="hidden" accept=".pdf,.doc,.docx" @change="handleFileChange" />

          <button type="button" @click="fileInput?.click()"
            class="mt-4 rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">

            Pilih File
          </button>
        </div>

        <!-- FILE BARU -->
        <div v-if="selectedFile" class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">

          <div class="flex items-center justify-between gap-3">

            <div class="flex items-center gap-3">

              <FileText class="h-4 w-4 text-emerald-600" />

              <div>

                <p class="text-sm font-semibold text-emerald-700">

                  {{ selectedFile.name }}
                </p>

                <p class="text-xs text-emerald-600">

                  {{
                    (
                      selectedFile.size
                      / 1024
                      / 1024
                    ).toFixed(2)
                  }}
                  MB
                </p>
              </div>
            </div>

            <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold text-emerald-700">

              File Baru
            </span>
          </div>
        </div>

        <!-- FILE LAMA -->
        <div v-else-if="existingFile" class="rounded-xl border border-blue-200 bg-blue-50 p-3">

          <div class="flex items-center justify-between gap-3">

            <div class="flex items-center gap-3">

              <FileText class="h-4 w-4 text-blue-600" />

              <div>

                <p class="text-sm font-semibold text-blue-700">

                  {{ existingFileName }}
                </p>

                <p class="text-xs text-blue-600">

                  File sudah dikumpulkan
                </p>
              </div>
            </div>

            <a :href="`/storage/${existingFile}`" target="_blank"
              class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">

              Lihat
            </a>
          </div>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end">

          <button @click="submitLkpd" :disabled="!canSubmit" :class="canSubmit
            ? 'bg-orange-500 hover:bg-orange-600'
            : 'cursor-not-allowed bg-slate-300 text-slate-500'
            " class="rounded-lg px-4 py-2 text-sm font-semibold text-white transition">

            {{
              canSubmit
                ? 'Upload LKPD'
                : 'Pilih File'
            }}
          </button>
        </div>
      </div>

      <!-- SUCCESS -->
      <div v-if="submitted"
        class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">

        <div class="flex items-center gap-2">

          <CheckCircle2 class="h-4 w-4" />

          LKPD berhasil dikumpulkan.
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <section class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <a :href="`/student/meetings/${props.meeting.id}/practice`"
        class="rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

        ← Kembali ke Praktik
      </a>

      <a v-if="
        evalOpened &&
        currentSubmission?.file_path
      " :href="`/student/meetings/${props.meeting.id}/evaluation`"
        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

        Lanjut ke Evaluation
      </a>

      <button v-else disabled
        class="cursor-not-allowed rounded-lg bg-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-500">

        Evaluation Terkunci
      </button>
    </section>
  </div>
</template>