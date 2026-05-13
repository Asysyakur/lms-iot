<!-- resources/js/pages/student/meetings/lkpd/Show.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';

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
  meetingId: number;
}>();

const lkpdLink = ref('');

const submitted = ref(false);

const instructions = [
  'Baca seluruh petunjuk pengerjaan LKPD terlebih dahulu.',
  'Buat salinan template Google Docs sebelum mulai mengerjakan.',
  'Isi seluruh jawaban sesuai hasil praktik yang telah dilakukan.',
  'Pastikan link Google Docs dapat diakses guru.',
];

const templateLink =
  'https://docs.google.com/document/d/xxxxxxxxxxxxx/copy';

const canSubmit = computed(() => {
  return lkpdLink.value.trim().length > 0;
});
</script>

<template>
  <div class="space-y-6">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-orange-600 to-amber-500 p-6 text-white shadow-lg"
    >
      <div
        class="absolute right-0 top-0 h-40 w-40 rounded-full bg-white/10"
      />

      <div>
        <span
          class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold"
        >
          Pertemuan {{ meetingId }}
        </span>

        <h1
          class="mt-4 text-3xl font-bold"
        >
          📄 LKPD:
          Sensor Cahaya pada
          Micro:bit
        </h1>

        <p
          class="mt-2 text-orange-100"
        >
          Kerjakan LKPD berdasarkan hasil
          praktik dan kumpulkan link
          dokumenmu.
        </p>
      </div>
    </section>

    <!-- FLOW -->
    <section
      class="rounded-3xl bg-white p-6 shadow-sm"
    >
      <div
        class="flex items-center justify-between"
      >
        <div
          class="flex flex-1 items-center"
        >
          <!-- MATERI -->
          <div
            class="flex flex-col items-center"
          >
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white"
            >
              ✓
            </div>

            <p
              class="mt-2 text-xs font-semibold text-emerald-600"
            >
              Materi
            </p>
          </div>

          <div
            class="mx-2 h-1 flex-1 rounded-full bg-emerald-500"
          />

          <!-- REFLEKSI -->
          <div
            class="flex flex-col items-center"
          >
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white"
            >
              ✓
            </div>

            <p
              class="mt-2 text-xs font-semibold text-emerald-600"
            >
              Refleksi
            </p>
          </div>

          <div
            class="mx-2 h-1 flex-1 rounded-full bg-emerald-500"
          />

          <!-- KUIS -->
          <div
            class="flex flex-col items-center"
          >
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white"
            >
              ✓
            </div>

            <p
              class="mt-2 text-xs font-semibold text-emerald-600"
            >
              Kuis
            </p>
          </div>

          <div
            class="mx-2 h-1 flex-1 rounded-full bg-emerald-500"
          />

          <!-- PRAKTIK -->
          <div
            class="flex flex-col items-center"
          >
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white"
            >
              ✓
            </div>

            <p
              class="mt-2 text-xs font-semibold text-emerald-600"
            >
              Praktik
            </p>
          </div>

          <div
            class="mx-2 h-1 flex-1 rounded-full bg-orange-500"
          />

          <!-- LKPD -->
          <div
            class="flex flex-col items-center"
          >
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white"
            >
              5
            </div>

            <p
              class="mt-2 text-xs font-semibold text-orange-600"
            >
              LKPD
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- INSTRUCTIONS -->
    <section
      class="rounded-3xl bg-white p-6 shadow-sm"
    >
      <div
        class="mb-6 flex items-center gap-4"
      >
        <div
          class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100"
        >
          <FileSpreadsheet
            class="h-6 w-6 text-orange-600"
          />
        </div>

        <div>
          <h2
            class="text-lg font-bold text-slate-800"
          >
            Instruksi Pengerjaan
          </h2>

          <p
            class="mt-1 text-sm text-slate-500"
          >
            Ikuti petunjuk berikut sebelum
            mengumpulkan LKPD.
          </p>
        </div>
      </div>

      <div class="space-y-4">
        <div
          v-for="(instruction, index) in instructions"
          :key="instruction"
          class="flex items-start gap-4 rounded-2xl border border-slate-200 p-4"
        >
          <div
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white"
          >
            {{ index + 1 }}
          </div>

          <p
            class="text-sm leading-relaxed text-slate-700"
          >
            {{ instruction }}
          </p>
        </div>
      </div>
    </section>

    <!-- TEMPLATE -->
    <section
      class="rounded-3xl border border-blue-200 bg-blue-50 p-6"
    >
      <div
        class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
      >
        <div
          class="flex items-start gap-4"
        >
          <div
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white"
          >
            <FileText
              class="h-7 w-7 text-blue-600"
            />
          </div>

          <div>
            <h2
              class="text-lg font-bold text-blue-700"
            >
              Template LKPD Google Docs
            </h2>

            <p
              class="mt-1 text-sm text-blue-600"
            >
              Klik tombol berikut untuk
              membuat salinan template
              LKPD.
            </p>
          </div>
        </div>

        <a
          :href="templateLink"
          target="_blank"
          class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
        >
          <ExternalLink
            class="h-5 w-5"
          />

          Buat Salinan LKPD
        </a>
      </div>
    </section>

    <!-- WARNING -->
    <section
      class="rounded-3xl border border-amber-200 bg-amber-50 p-5"
    >
      <div
        class="flex items-start gap-4"
      >
        <div
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100"
        >
          <Lock
            class="h-5 w-5 text-amber-600"
          />
        </div>

        <div>
          <h2
            class="font-bold text-amber-700"
          >
            Ketentuan Pengumpulan
          </h2>

          <p
            class="mt-1 text-sm leading-relaxed text-amber-700"
          >
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
    <section
      class="rounded-3xl bg-white p-6 shadow-sm"
    >
      <div
        class="mb-6 flex items-center gap-4"
      >
        <div
          class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100"
        >
          <Upload
            class="h-6 w-6 text-emerald-600"
          />
        </div>

        <div>
          <h2
            class="text-lg font-bold text-slate-800"
          >
            Pengumpulan LKPD
          </h2>

          <p
            class="mt-1 text-sm text-slate-500"
          >
            Tempelkan link Google Docs
            LKPD yang sudah dikerjakan.
          </p>
        </div>
      </div>

      <div class="space-y-5">
        <div>
          <label
            class="mb-2 block text-sm font-semibold text-slate-700"
          >
            Link Google Docs LKPD
          </label>

          <input
            v-model="lkpdLink"
            type="text"
            placeholder="https://docs.google.com/document/..."
            class="w-full rounded-2xl border border-slate-200 px-4 py-4 outline-none transition focus:border-orange-500"
          />
        </div>

        <div
          class="flex justify-end"
        >
          <button
            :disabled="!canSubmit"
            :class="
              canSubmit
                ? 'bg-orange-500 hover:bg-orange-600'
                : 'cursor-not-allowed bg-slate-300 text-slate-500'
            "
            class="rounded-2xl px-6 py-3 font-semibold text-white transition"
          >
            {{
              canSubmit
                ? '🚀 Kumpulkan LKPD'
                : '🔒 Masukkan Link LKPD'
            }}
          </button>
        </div>
      </div>

      <!-- SUCCESS -->
      <div
        v-if="submitted"
        class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700"
      >
        ✅ LKPD berhasil dikumpulkan.
      </div>
    </section>

    <!-- FOOTER -->
    <section
      class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
    >
      <button
        class="rounded-2xl border border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
      >
        ← Kembali ke Praktik
      </button>

      <div
        class="rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white"
      >
        🎉 Semua Tahapan Pertemuan
        Selesai
      </div>
    </section>
  </div>
</template>