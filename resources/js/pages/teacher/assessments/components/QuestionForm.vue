<!-- resources/js/pages/teacher/assessments/components/QuestionForm.vue -->

<script setup lang="ts">
import { computed } from 'vue';

import {
  Plus,
  FileQuestion,
} from 'lucide-vue-next';

const props = defineProps<{
  type: 'pretest' | 'posttest';
}>();

const isPretest =
  computed(() =>
    props.type === 'pretest',
  );
</script>

<template>
  <div
    class="rounded-3xl bg-white p-6 shadow-sm"
  >
    <div
      class="mb-6 flex items-center gap-4"
    >
      <div
        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100"
      >
        <FileQuestion
          class="h-7 w-7 text-emerald-600"
        />
      </div>

      <div>
        <h2
          class="text-xl font-bold text-slate-800"
        >
          Form Soal
        </h2>

        <p
          class="mt-1 text-sm text-slate-500"
        >
          Tambahkan soal dan
          kunci jawaban.
        </p>
      </div>
    </div>

    <div class="space-y-5">
      <!-- TYPE -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Tipe Soal
        </label>

        <select
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500"
        >
          <option>
            Pilihan Ganda
          </option>

          <option
            v-if="!isPretest"
          >
            Uraian
          </option>
        </select>
      </div>

      <!-- QUESTION -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Pertanyaan
        </label>

        <textarea
          rows="4"
          placeholder="Masukkan pertanyaan..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none focus:border-emerald-500"
        />
      </div>

      <!-- OPTIONS -->
      <div
        class="grid gap-5 md:grid-cols-2"
      >
        <input
          type="text"
          placeholder="Opsi A"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500"
        />

        <input
          type="text"
          placeholder="Opsi B"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500"
        />

        <input
          type="text"
          placeholder="Opsi C"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500"
        />

        <input
          type="text"
          placeholder="Opsi D"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500"
        />
      </div>

      <!-- ANSWER -->
      <div>
        <label
          class="mb-2 block text-sm font-semibold text-slate-700"
        >
          Kunci Jawaban
        </label>

        <select
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500"
        >
          <option>
            A
          </option>

          <option>
            B
          </option>

          <option>
            C
          </option>

          <option>
            D
          </option>
        </select>
      </div>

      <!-- BUTTON -->
      <button
        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white transition hover:bg-emerald-600"
      >
        <Plus class="h-5 w-5" />

        Tambahkan Soal
      </button>
    </div>
  </div>
</template>