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
  <div class="rounded-3xl bg-white p-5 shadow-sm">

    <div class="mb-5 flex items-center gap-3">

      <div
        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100">
        <FileQuestion class="h-6 w-6 text-emerald-600" />
      </div>

      <div>
        <h2 class="text-lg font-bold text-slate-800">
          Form Soal
        </h2>
      </div>

    </div>

    <div class="space-y-4">

      <!-- TYPE -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Tipe Soal
        </label>

        <select
          v-model="form.question_type"
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500">

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
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Pertanyaan
        </label>

        <textarea
          v-model="form.question"
          rows="4"
          placeholder="Masukkan pertanyaan..."
          class="w-full rounded-2xl border border-slate-200 p-4 text-sm outline-none focus:border-emerald-500" />
      </div>

      <!-- OPTIONS -->
      <div
        v-if="form.question_type === 'multiple_choice'"
        class="grid gap-3">

        <input
          v-model="form.option_a"
          type="text"
          placeholder="Opsi A"
          class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500" />

        <input
          v-model="form.option_b"
          type="text"
          placeholder="Opsi B"
          class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500" />

        <input
          v-model="form.option_c"
          type="text"
          placeholder="Opsi C"
          class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500" />

        <input
          v-model="form.option_d"
          type="text"
          placeholder="Opsi D"
          class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500" />

        <input
          v-model="form.option_e"
          type="text"
          placeholder="Opsi E"
          class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500" />
      </div>

      <!-- ANSWER -->
      <select
        v-if="form.question_type === 'multiple_choice'"
        v-model="form.answer"
        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-500">

        <option value="A">
          A
        </option>

        <option value="B">
          B
        </option>

        <option value="C">
          C
        </option>

        <option value="D">
          D
        </option>

        <option value="E">
          E
        </option>

      </select>

      <!-- BUTTON -->
      <button
        @click="submit"
        type="button"
        :disabled="form.processing"
        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">

        <Plus class="h-4 w-4" />

        {{
          form.id
            ? 'Update Soal'
            : 'Tambahkan Soal'
        }}
      </button>
    </div>
  </div>
</template>
```
