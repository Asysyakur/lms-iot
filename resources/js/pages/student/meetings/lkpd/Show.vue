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

const templateLink =
  'https://docs.google.com/document/d/xxxxxxxxxxxxx/copy';

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

  selectedFile.value =
    event.dataTransfer.files[0];
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

  selectedFile.value =
    target.files[0];
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
  <div class="space-y-6">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-orange-600 to-amber-500 p-6 text-white shadow-lg">
      <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-white/10" />

      <div>
        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
          Pertemuan {{ meetingId }}
        </span>

        <h1 class="mt-4 text-3xl font-bold">
          📄 LKPD:
          Sensor Cahaya pada
          Micro:bit
        </h1>

        <p class="mt-2 text-orange-100">
          Kerjakan LKPD berdasarkan hasil
          praktik dan kumpulkan link
          dokumenmu.
        </p>
      </div>
    </section>

    <!-- FLOW -->
    <section class="rounded-2xl bg-white p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex flex-1 items-center">
          <!-- MATERI -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white">
              ✓
            </div>

            <p class="mt-2 text-xs font-semibold text-emerald-600">
              Materi
            </p>
          </div>

          <div class="mx-2 h-1 flex-1 rounded-full bg-emerald-500" />

          <!-- REFLEKSI -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white">
              ✓
            </div>

            <p class="mt-2 text-xs font-semibold text-emerald-600">
              Refleksi
            </p>
          </div>

          <div class="mx-2 h-1 flex-1 rounded-full bg-emerald-500" />

          <!-- KUIS -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white">
              ✓
            </div>

            <p class="mt-2 text-xs font-semibold text-emerald-600">
              Kuis
            </p>
          </div>

          <div class="mx-2 h-1 flex-1 rounded-full bg-emerald-500" />

          <!-- PRAKTIK -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white">
              ✓
            </div>

            <p class="mt-2 text-xs font-semibold text-emerald-600">
              Praktik
            </p>
          </div>

          <div class="mx-2 h-1 flex-1 rounded-full bg-orange-500" />

          <!-- LKPD -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white">
              5
            </div>

            <p class="mt-2 text-xs font-semibold text-orange-600">
              LKPD
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- INSTRUCTIONS -->
    <section class="rounded-2xl bg-white p-5 shadow-sm">
      <div class="mb-6 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100">
          <FileSpreadsheet class="h-6 w-6 text-orange-600" />
        </div>

        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Instruksi Pengerjaan
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Ikuti petunjuk berikut sebelum
            mengumpulkan LKPD.
          </p>
        </div>
      </div>

      <div class="space-y-4">
        <div v-for="(instruction, index) in instructions" :key="instruction"
          class="flex items-start gap-4 rounded-2xl border border-slate-200 p-4">
          <div
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white">
            {{ index + 1 }}
          </div>

          <p class="text-sm leading-relaxed text-slate-700">
            {{ instruction }}
          </p>
        </div>
      </div>
    </section>

    <!-- TEMPLATE -->
    <section class="rounded-3xl border border-blue-200 bg-blue-50 p-6">
      <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-start gap-4">
          <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white">
            <FileText class="h-7 w-7 text-blue-600" />
          </div>

          <div>
            <h2 class="text-lg font-bold text-blue-700">
              Template LKPD Google Docs
            </h2>

            <p class="mt-1 text-sm text-blue-600">
              Klik tombol berikut untuk
              membuat salinan template
              LKPD.
            </p>
          </div>
        </div>

        <a :href="templateLink" target="_blank"
          class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
          <ExternalLink class="h-5 w-5" />

          Buat Salinan LKPD
        </a>
      </div>
    </section>

    <!-- WARNING -->
    <section class="rounded-3xl border border-amber-200 bg-amber-50 p-5">
      <div class="flex items-start gap-4">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
          <Lock class="h-5 w-5 text-amber-600" />
        </div>

        <div>
          <h2 class="font-bold text-amber-700">
            Ketentuan Pengumpulan
          </h2>

          <p class="mt-1 text-sm leading-relaxed text-amber-700">
            Pastikan akses Google Docs
            diatur ke
            <span class="font-semibold">
              "Siapa saja yang memiliki
              link"
            </span>
            sebelum dikumpulkan.
          </p>
        </div>
      </div>
    </section>

    <!-- SUBMIT -->
    <section class="rounded-2xl bg-white p-5 shadow-sm">
      <div class="mb-6 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100">
          <Upload class="h-6 w-6 text-emerald-600" />
        </div>

        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Pengumpulan LKPD
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Upload file LKPD hasil
            pengerjaanmu.
          </p>
        </div>
      </div>

      <div class="space-y-5">

        <!-- DROPZONE -->
        <div @drop="handleDrop" @dragover="handleDragOver" @dragleave="handleDragLeave" :class="isDragging
          ? 'border-orange-500 bg-orange-100 scale-[1.01]'
          : 'border-orange-300 bg-orange-50'
          "
          class="flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed px-6 py-14 text-center transition">

          <Upload class="mb-4 h-12 w-12 text-orange-500" />

          <h3 class="text-lg font-bold text-slate-700">
            Klik atau Seret File ke Sini
          </h3>

          <p class="mt-2 text-sm text-slate-500">
            Format didukung:
            PDF, DOCX, JPG, PNG
            (maks 10 MB)
          </p>

          <input ref="fileInput" type="file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
            @change="handleFileChange" />

          <button type="button" @click="$refs.fileInput.click()"
            class="mt-5 rounded-2xl bg-orange-500 px-5 py-2 font-semibold text-white transition hover:bg-orange-600">
            Pilih File
          </button>
        </div>

        <!-- FILE BARU DIPILIH -->
        <div v-if="selectedFile" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
          <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <FileText class="h-5 w-5 text-emerald-600" />

              <div>
                <p class="font-semibold text-emerald-700">
                  {{ selectedFile.name }}
                </p>

                <p class="text-sm text-emerald-600">
                  {{
                    (
                      selectedFile.size /
                      1024 /
                      1024
                    ).toFixed(2)
                  }}
                  MB
                </p>
              </div>
            </div>

            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
              File Baru
            </span>
          </div>
        </div>

        <!-- FILE LAMA DARI DB -->
        <div v-else-if="existingFile" class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
          <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <FileText class="h-5 w-5 text-blue-600" />

              <div>
                <p class="font-semibold text-blue-700">
                  {{ existingFileName }}
                </p>

                <p class="text-sm text-blue-600">
                  File LKPD yang sudah dikumpulkan
                </p>
              </div>
            </div>

            <a :href="`/storage/${existingFile}`" target="_blank"
              class="rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
              Lihat File
            </a>
          </div>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end">
          <button @click="submitLkpd" :disabled="!canSubmit" :class="canSubmit
            ? 'bg-orange-500 hover:bg-orange-600'
            : 'cursor-not-allowed bg-slate-300 text-slate-500'
            " class="rounded-2xl px-6 py-3 font-semibold text-white transition">
            {{
              canSubmit
                ? '🚀 Upload LKPD Sekarang'
                : '🔒 Pilih File LKPD'
            }}
          </button>
        </div>
      </div>

      <!-- SUCCESS -->
      <div v-if="submitted"
        class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
        ✅ LKPD berhasil dikumpulkan.
      </div>
    </section>

    <!-- FOOTER -->
    <section class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <a
        :href="`/student/meetings/${props.meeting.id}/practice`"
        class="rounded-2xl border border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
        ← Kembali ke Praktik
      </a>

      <a v-if="evalOpened && currentSubmission?.file_path" :href="`/student/meetings/${props.meeting.id}/evaluation`"
        class="rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
        🚀 Lanjut ke Evaluation
      </a>

      <button v-else class="rounded-2xl bg-slate-300 px-6 py-3 font-semibold text-slate-500">
        🔒 Lanjut ke Evaluation
      </button>
    </section>
  </div>
</template>