<script setup lang="ts">
import {
  useForm,
} from '@inertiajs/vue3';

import {
  Plus,
  Pencil,
  Trash2,
} from 'lucide-vue-next';

import axios from 'axios';

import {
  ref,
  watch,
} from 'vue';

import {
  toast,
} from 'vue-sonner';

const props = defineProps<{
  meeting: any;
}>();

const editingQuiz =
  ref<any>(null);

const form = useForm({
  id: null,

  meeting_id:
    props.meeting.id,

  question: '',

  option_a: '',
  option_b: '',
  option_c: '',
  option_d: '',

  answer: 'A',
});

/**
 * RESET FORM
 * SAAT GANTI MEETING
 */
watch(
  () => props.meeting,
  (meeting) => {

    if (!meeting) {
      return;
    }

    editingQuiz.value =
      null;

    form.reset();

    form.meeting_id =
      meeting.id;

    form.answer = 'A';
  },
  {
    immediate: true,
  },
);

/**
 * EDIT QUIZ
 */
const editQuiz = (
  quiz: any,
) => {

  editingQuiz.value =
    quiz;

  form.id =
    quiz.id;

  form.question =
    quiz.question;

  form.option_a =
    quiz.option_a;

  form.option_b =
    quiz.option_b;

  form.option_c =
    quiz.option_c;

  form.option_d =
    quiz.option_d;

  form.answer =
    quiz.answer;
};

/**
 * SUBMIT
 */
const submit = async () => {

  try {

    /**
     * UPDATE
     */
    if (form.id) {

      const response =
        await axios.put(
          `/teacher/quizzes/${form.id}`,
          form,
        );

      const index =
        props.meeting.quizzes.findIndex(
          (quiz: any) =>
            quiz.id ===
            form.id,
        );

      if (index !== -1) {

        props.meeting.quizzes[
          index
        ] =
          response.data.quiz;
      }

      toast.success(
        'Soal berhasil diupdate'
      );

      resetForm();

      return;
    }

    /**
     * CREATE
     */
    const response =
      await axios.post(
        '/teacher/quizzes',
        form,
      );

    props.meeting.quizzes.push(
      response.data.quiz,
    );

    toast.success(
      'Soal berhasil ditambahkan'
    );

    resetForm();

  } catch (error) {

    toast.error(
      'Terjadi kesalahan'
    );
  }
};

/**
 * DELETE
 */
const removeQuiz =
  async (
    id: number,
  ) => {

    const confirmed =
      confirm(
        'Hapus soal ini?'
      );

    if (!confirmed) {
      return;
    }

    try {

      await axios.delete(
        `/teacher/quizzes/${id}`,
      );

      props.meeting.quizzes =
        props.meeting.quizzes.filter(
          (quiz: any) =>
            quiz.id !== id,
        );

      toast.success(
        'Soal berhasil dihapus'
      );

    } catch (error) {

      toast.error(
        'Gagal menghapus soal'
      );
    }
  };

/**
 * RESET
 */
const resetForm = () => {

  editingQuiz.value =
    null;

  form.reset();

  form.meeting_id =
    props.meeting.id;

  form.answer = 'A';
};

const toggleQuizMeeting =
  async () => {

    if (
      !props.meeting.quizzes
        ?.length
    ) {
      return;
    }

    try {

      const response =
        await axios.patch(
          '/teacher/quizzes/toggle-by-meeting',
          {
            meeting_id:
              props.meeting.id,
          },
        );

      props.meeting.quizzes.forEach(
        (quiz: any) => {

          quiz.is_active =
            response.data.is_active;
        },
      );

      toast.success(
        response.data.is_active
          ? 'Quiz diaktifkan'
          : 'Quiz dinonaktifkan'
      );

    } catch (error) {

      toast.error(
        'Gagal mengubah status quiz'
      );
    }
  };
</script>

<template>
  <section class="rounded-3xl bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-bold text-slate-800">
        📝 Pengaturan Kuis
      </h2>

      <div class="flex items-center gap-3">
        <button v-if="editingQuiz" @click="resetForm"
          class="cursor-pointer rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
          Batal Edit
        </button>

        <button v-if="meeting.quizzes?.length" @click="toggleQuizMeeting"
          class="cursor-pointer rounded-xl px-4 py-2 text-sm font-semibold transition" :class="meeting.quizzes?.[0]?.is_active
            ? 'bg-emerald-500 text-white hover:bg-emerald-600'
            : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
            ">
          {{
            meeting.quizzes?.[0]?.is_active
              ? 'Quiz Aktif'
              : 'Quiz Draft'
          }}
        </button>
      </div>
    </div>

    <!-- FORM -->
    <div class="mt-6 space-y-5">
      <!-- QUESTION -->
      <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
          Pertanyaan
        </label>

        <textarea v-model="form.question" rows="4" class="w-full rounded-2xl border border-slate-200 p-4" />
      </div>

      <!-- OPTIONS -->
      <div class="grid gap-5 md:grid-cols-2">
        <input v-model="form.option_a" type="text" placeholder="Opsi A"
          class="rounded-2xl border border-slate-200 px-4 py-3" />

        <input v-model="form.option_b" type="text" placeholder="Opsi B"
          class="rounded-2xl border border-slate-200 px-4 py-3" />

        <input v-model="form.option_c" type="text" placeholder="Opsi C"
          class="rounded-2xl border border-slate-200 px-4 py-3" />

        <input v-model="form.option_d" type="text" placeholder="Opsi D"
          class="rounded-2xl border border-slate-200 px-4 py-3" />
      </div>

      <!-- ANSWER -->
      <select v-model="form.answer" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
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
      </select>

      <!-- BUTTON -->
      <button @click="submit" :disabled="form.processing"
        class="cursor-pointer inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white hover:bg-emerald-600 disabled:opacity-50">
        <Plus class="h-5 w-5" />

        {{
          editingQuiz
            ? 'Update Soal'
            : 'Tambah Soal'
        }}
      </button>
    </div>

    <!-- TABLE -->
    <div class="mt-10">
      <h3 class="mb-4 text-lg font-bold text-slate-800">
        Daftar Soal
      </h3>

      <div class="space-y-4">
        <div v-for="quiz in meeting.quizzes" :key="quiz.id" class="rounded-2xl border border-slate-200 p-5">
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-3">
                <h4 class="font-semibold text-slate-800">
                  {{ quiz.question }}
                </h4>
              </div>

              <p class="mt-2 text-sm text-slate-500">
                Jawaban:
                {{ quiz.answer }}
              </p>
            </div>

            <div class="flex items-center gap-2">
              <button @click="editQuiz(quiz)"
                class="cursor-pointer rounded-xl bg-slate-100 p-3 text-slate-700 hover:bg-slate-200">
                <Pencil class="h-4 w-4" />
              </button>

              <button @click="removeQuiz(quiz.id)"
                class="cursor-pointer rounded-xl bg-red-500 p-3 text-white hover:bg-red-600">
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>

        <div v-if="!meeting.quizzes?.length"
          class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
          Belum ada soal quiz
        </div>
      </div>
    </div>
  </section>
</template>