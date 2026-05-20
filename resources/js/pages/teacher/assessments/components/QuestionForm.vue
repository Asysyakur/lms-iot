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

const isPretest =
  computed(() =>
    props.type === 'pretest',
  );

const form = useForm({
  id: null,

  type: props.type,

  question_type: 'multiple_choice',

  question: '',

  option_a: '',
  option_b: '',
  option_c: '',
  option_d: '',

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

    form.answer =
      question.answer;
  },
  {
    immediate: true,
  },
);

const submit = () => {

  /**
   * UPDATE
   */
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

        onError: () => {

          toast.error(
            'Gagal memperbarui soal'
          );
        },
      },
    );

    return;
  }

  /**
   * CREATE
   */
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

      onError: () => {

        toast.error(
          'Gagal menambahkan soal'
        );
      },
    },
  );
};

</script>

<template>
  <div class="rounded-3xl bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center gap-4">
      <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100">
        <FileQuestion class="h-7 w-7 text-emerald-600" />
      </div>

      <div>
        <h2 class="text-xl font-bold text-slate-800">
          Form Soal
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Tambahkan soal dan
          kunci jawaban.
        </p>
      </div>
    </div>

    <div class="space-y-5">
      <!-- TYPE -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Tipe Soal
        </label>

        <select v-model="form.question_type"
          class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500">
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

        <textarea v-model="form.question" rows="4" placeholder="Masukkan pertanyaan..."
          class="w-full rounded-2xl border border-slate-200 p-4 outline-none focus:border-emerald-500" />
      </div>

      <div v-if="form.question_type === 'multiple_choice'" class="space-y-4 grid grid-cols-1">
        <input v-model="form.option_a" type="text" placeholder="Opsi A"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500" />

        <input v-model="form.option_b" type="text" placeholder="Opsi B"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500" />

        <input v-model="form.option_c" type="text" placeholder="Opsi C"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500" />

        <input v-model="form.option_d" type="text" placeholder="Opsi D"
          class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500" />
      </div>

      <!-- ANSWER -->
      <select v-if="form.question_type === 'multiple_choice'" v-model="form.answer"
        class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500">
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
      </select>

      <!-- BUTTON -->
      <button @click="submit" type="button" :disabled="form.processing"
        class="cursor-pointer inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white transition hover:bg-emerald-600">
        <Plus class="h-5 w-5" />

        {{
          form.id
            ? 'Update Soal'
            : 'Tambahkan Soal'
        }}
      </button>
    </div>
  </div>
</template>