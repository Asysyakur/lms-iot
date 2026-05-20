<!-- resources/js/pages/student/meetings/practices/Show.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
  FlaskConical,
  CheckCircle2,
  ExternalLink,
  Link as LinkIcon,
  Lock,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  meeting: any;
  practice: any;
  submission: any;
}>();

const practiceLink = ref(
  props.submission?.project_url || ''
);

const submitted = ref(
  !!props.submission
);

const instructions = computed(() => {

  if (!props.practice?.instruction) {
    return [];
  }

  return props.practice.instruction
    .split('\n')
    .filter(item => item.trim() !== '');

});

const canSubmit = computed(() => {
  return practiceLink.value.trim().length > 0;
});

const lkpdOpened = computed(() => {
  return props.meeting.lkpd?.is_active;
});

console.log(props.meeting, lkpdOpened.value, props.submission?.project_url);

const submitPractice = async () => {

  try {

    await axios.post(
      `/student/meetings/${props.meeting.id}/practice/submit`,
      {
        project_url:
          practiceLink.value,
      }
    );

    submitted.value = true;

    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: 'Praktikum berhasil dikumpulkan',
      timer: 2000,
      showConfirmButton: false,
    });

  } catch (error: any) {

    Swal.fire({
      icon: 'error',
      title: 'Gagal',

      text:
        error.response?.data?.errors?.project_url?.[0]
        ||
        'Link tidak valid',
    });

  }

};
</script>

<template>
  <div class="space-y-6">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 to-cyan-600 p-6 text-white shadow-lg">
      <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-white/10" />

      <div>
        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
          Pertemuan {{ props.meeting.meeting_number }} : {{ props.meeting.title }}
        </span>

        <h1 class="mt-4 text-3xl font-bold">
          🧪 Praktik:
          {{ props.meeting.title || 'Praktik Belum Tersedia' }}
        </h1>

        <p class="mt-2 text-emerald-100">
          Kerjakan praktikum menggunakan
          MakeCode dan kumpulkan link
          proyekmu.
        </p>
      </div>
    </section>

    <!-- FLOW -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
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

          <div class="mx-2 h-1 flex-1 rounded-full bg-blue-500" />

          <!-- PRAKTIK -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
              4
            </div>

            <p class="mt-2 text-xs font-semibold text-blue-600">
              Praktik
            </p>
          </div>

          <div class="mx-2 h-1 flex-1 rounded-full bg-slate-200" />

          <!-- LKPD -->
          <div class="flex flex-col items-center">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-500">
              5
            </div>

            <p class="mt-2 text-xs font-semibold text-slate-500">
              LKPD
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- INSTRUCTIONS -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <div class="mb-6 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100">
          <FlaskConical class="h-6 w-6 text-blue-600" />
        </div>

        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Instruksi Praktikum
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Ikuti langkah berikut untuk
            menyelesaikan praktik.
          </p>
        </div>
      </div>

      <div class="space-y-4">
        <div v-for="(instruction, index) in instructions" :key="instruction"
          class="flex items-center gap-4 rounded-2xl border border-slate-200 p-4">
          <div
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
            {{ index + 1 }}
          </div>

          <p class="text-sm leading-relaxed text-slate-700">
            {{ instruction }}
          </p>
        </div>
      </div>
    </section>

    <!-- MAKECODE -->
    <section class="rounded-3xl border border-blue-200 bg-blue-50 p-6">
      <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <h2 class="text-lg font-bold text-blue-700">
            💻 Buka MakeCode Editor
          </h2>

          <p class="mt-1 text-sm text-blue-600">
            Gunakan MakeCode untuk membuat
            program Microbit.
          </p>
        </div>

        <a :href="props.practice.makecode_url || 'https://makecode.microbit.org/'" target="_blank"
          class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
          <ExternalLink class="h-5 w-5" />

          Buka MakeCode
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
            Pastikan link MakeCode dapat
            diakses oleh guru sebelum
            dikumpulkan.
          </p>
        </div>
      </div>
    </section>

    <!-- SUBMIT -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <div class="mb-6 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100">
          <LinkIcon class="h-6 w-6 text-emerald-600" />
        </div>

        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Pengumpulan Praktikum
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Tempelkan link hasil proyek
            MakeCode kamu.
          </p>
        </div>
      </div>

      <div class="space-y-5">
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Link MakeCode Proyek
          </label>

          <input v-model="practiceLink" type="text" placeholder="https://makecode.microbit.org/..."
            class="w-full rounded-2xl border border-slate-200 px-4 py-4 outline-none transition focus:border-blue-500" />
        </div>

        <div class="flex justify-end">
          <button @click="submitPractice" :disabled="!canSubmit" :class="canSubmit
            ? 'bg-emerald-500 hover:bg-emerald-600'
            : 'cursor-not-allowed bg-slate-300 text-slate-500'
            " class="rounded-2xl px-6 py-3 font-semibold text-white transition">
            {{
              canSubmit
                ? '🚀 Kumpulkan Praktikum'
                : '🔒 Masukkan Link Praktikum'
            }}
          </button>
        </div>
      </div>

      <!-- SUCCESS -->
      <div v-if="submitted"
        class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
        ✅ Praktikum berhasil
        dikumpulkan.
      </div>
    </section>

    <!-- FOOTER -->
    <section class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <button
        class="rounded-2xl border border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
        ← Kembali ke Kuis
      </button>

      <a v-if="lkpdOpened && props.submission?.project_url" :href="`/student/meetings/${props.meeting.id}/lkpd`"
        class="rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
        🚀 Lanjut ke LKPD
      </a>

      <button v-else class="rounded-2xl bg-slate-300 px-6 py-3 font-semibold text-slate-500">
        🔒 Menunggu LKPD Dibuka Guru
      </button>
    </section>
  </div>
</template>