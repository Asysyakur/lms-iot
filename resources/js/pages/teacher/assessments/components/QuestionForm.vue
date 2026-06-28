```vue
<!-- resources/js/pages/teacher/assessments/components/QuestionForm.vue -->

<script setup lang="ts">
import {
  computed,
  watch,
} from 'vue';

import { useForm } from '@inertiajs/vue3';

import { toast } from 'vue-sonner';

import {
  Plus,
  FileQuestion,
} from 'lucide-vue-next';

const props = defineProps<{
  type: 'pretest' | 'posttest';
  editingQuestion?: any;
}>();

const form = useForm({
  id: null,

  type: props.type,

  question_type: 'multiple_choice',

  question: '',

  code: '',

  option_a: '',
  option_b: '',
  option_c: '',
  option_d: '',
  option_e: '',

  answer: 'A',
});

watch(
  () => props.editingQuestion,
  (question) => {

    if (!question) {
      return;
    }

    form.id = question.id;

    form.question_type =
      question.type;

    form.question =
      question.question;

    form.code =
      question.code ?? '';

    form.option_a =
      question.option_a;

    form.option_b =
      question.option_b;

    form.option_c =
      question.option_c;

    form.option_d =
      question.option_d;

    form.option_e =
      question.option_e;

    form.answer =
      question.answer;
  },
  {
    immediate: true,
  },
);

const submit = () => {

  if (form.id) {

    form.put(
      `/teacher/questions/${form.id}`,
      {
        preserveScroll: true,

        onSuccess: () => {

          toast.success(
            'Soal berhasil diperbarui'
          );

          form.reset();

          form.type =
            props.type;

          form.id = null;
        },
      },
    );

    return;
  }

  form.post(
    '/teacher/questions',
    {
      preserveScroll: true,

      onSuccess: () => {

        toast.success(
          'Soal berhasil ditambahkan'
        );

        form.reset();

        form.type =
          props.type;
      },
    },
  );
};
</script>

<template>
  <div class="rounded-xl bg-white p-4 shadow-sm">

    <!-- HEADER -->
    <div class="mb-4 flex items-center gap-3">

      <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">

        <FileQuestion class="h-5 w-5 text-emerald-600" />
      </div>

      <div>

        <h2 class="text-sm font-bold text-slate-800">

          Form Soal
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Tambahkan atau edit soal
          assessment.
        </p>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="space-y-4">

      <!-- TYPE -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Tipe Soal
        </label>

        <select v-model="form.question_type"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">

          <option value="multiple_choice">
            Pilihan Ganda
          </option>

          <option value="essay">
            Uraian
          </option>
        </select>
      </div>

      <!-- QUESTION -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Pertanyaan
        </label>

        <textarea v-model="form.question" rows="4" placeholder="Masukkan pertanyaan..."
          class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
      </div>

      <!-- CODE -->
      <div>

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Kode Program
          <span class="font-normal text-slate-400">
            (opsional)
          </span>
        </label>

        <textarea v-model="form.code" rows="6" placeholder="Tempel kode program di sini jika soal membutuhkan..."
          class="w-full rounded-xl border border-slate-200 bg-slate-900 px-4 py-3 font-mono text-sm text-emerald-300 outline-none transition focus:border-emerald-500" />

        <p class="mt-1.5 text-xs text-slate-400">

          Kode akan ditampilkan terpisah
          dari teks soal pada tampilan siswa.
        </p>
      </div>

      <!-- OPTIONS -->
      <div v-if="
        form.question_type ===
        'multiple_choice'
      " class="space-y-3">

        <input v-model="form.option_a" type="text" placeholder="Opsi A"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_b" type="text" placeholder="Opsi B"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_c" type="text" placeholder="Opsi C"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_d" type="text" placeholder="Opsi D"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_e" type="text" placeholder="Opsi E"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />
      </div>

      <!-- ANSWER -->
      <div v-if="
        form.question_type ===
        'multiple_choice'
      ">

        <label class="mb-2 block text-xs font-semibold text-slate-700">

          Jawaban Benar
        </label>

        <select v-model="form.answer"
          class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">

          <option value="A">
            Jawaban A
          </option>

          <option value="B">
            Jawaban B
          </option>

          <option value="C">
            Jawaban C
          </option>

          <option value="D">
            Jawaban D
          </option>

          <option value="E">
            Jawaban E
          </option>
        </select>
      </div>

      <!-- BUTTON -->
      <button @click="submit" type="button" :disabled="form.processing"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-50">

        <Plus class="h-4 w-4" />

        {{
          form.processing
            ? 'Menyimpan...'
            : form.id
              ? 'Update Soal'
              : 'Tambahkan Soal'
        }}
      </button>
    </div>
  </div>
</template>
```
