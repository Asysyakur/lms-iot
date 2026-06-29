<!-- resources/js/pages/teacher/meetings/components/QuizTab.vue -->

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
  code: '',

  option_a: '',
  option_b: '',
  option_c: '',
  option_d: '',
  option_e: '',

  answer: 'A',
});

/**
 * RESET FORM
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
const editQuiz =
  (
    quiz: any,
  ) => {

    editingQuiz.value =
      quiz;

    form.id =
      quiz.id;

    form.question =
      quiz.question;

    form.code =
      quiz.code ?? '';

    form.option_a =
      quiz.option_a;

    form.option_b =
      quiz.option_b;

    form.option_c =
      quiz.option_c;

    form.option_d =
      quiz.option_d;

    form.option_e =
      quiz.option_e ?? '';

    form.answer =
      quiz.answer;
  };

/**
 * SUBMIT
 */
const submit =
  async () => {

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
const resetForm =
  () => {

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
  <section class="rounded-xl bg-white p-4 shadow-sm">

    <!-- HEADER -->
    <div class="flex items-start justify-between gap-3">

      <div>

        <h2 class="text-sm font-bold text-slate-800">

          Pengaturan Kuis
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          Kelola soal dan jawaban kuis.
        </p>
      </div>

      <!-- ACTION -->
      <div class="flex items-center gap-2 shrink-0">

        <button v-if="editingQuiz" @click="resetForm"
          class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">

          Batal
        </button>

        <button v-if="meeting.quizzes?.length" @click="toggleQuizMeeting"
          class="rounded-lg px-3 py-2 text-xs font-semibold transition" :class="meeting.quizzes?.[0]?.is_active
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
    <div class="mt-5 space-y-4">

      <!-- QUESTION -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Pertanyaan
        </label>

        <textarea v-model="form.question" rows="4" placeholder="Masukkan pertanyaan..."
          class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
      </div>

      <!-- CODE -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Kode Program
          <span class="font-normal text-slate-400">
            (opsional)
          </span>
        </label>

        <textarea v-model="form.code" rows="6" placeholder="Tempel kode program di sini jika soal membutuhkan..."
          class="w-full rounded-lg border border-slate-200 bg-slate-900 px-4 py-3 font-mono text-sm text-emerald-300 outline-none transition focus:border-emerald-500" />

        <p class="mt-1.5 text-xs text-slate-400">

          Kode akan ditampilkan terpisah
          dari teks soal pada tampilan siswa.
        </p>
      </div>

      <!-- OPTIONS -->
      <div class="grid gap-3 md:grid-cols-2">

        <input v-model="form.option_a" type="text" placeholder="Opsi A"
          class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_b" type="text" placeholder="Opsi B"
          class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_c" type="text" placeholder="Opsi C"
          class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_d" type="text" placeholder="Opsi D"
          class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />

        <input v-model="form.option_e" type="text" placeholder="Opsi E"
          class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500 md:col-span-2" />
      </div>

      <!-- ANSWER -->
      <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">

          Jawaban Benar
        </label>

        <select v-model="form.answer"
          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">

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
      <div class="pt-1">

        <button @click="submit" :disabled="form.processing"
          class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200 disabled:opacity-50">

          <Plus class="h-4 w-4" />

          {{
            editingQuiz
              ? 'Update Soal'
              : 'Tambah Soal'
          }}
        </button>
      </div>
    </div>

    <!-- TABLE -->
    <div class="mt-7">

      <h3 class="mb-3 text-sm font-bold text-slate-800">

        Daftar Soal
      </h3>

      <div class="space-y-3">

        <!-- ITEM -->
        <div v-for="quiz in meeting.quizzes" :key="quiz.id" class="rounded-lg border border-slate-200 p-4">

          <div class="flex items-start justify-between gap-4">

            <!-- CONTENT -->
            <div class="flex-1">

              <h4 class="text-sm font-semibold leading-relaxed text-slate-800">

                {{ quiz.question }}
              </h4>

              <!-- CODE -->
              <pre v-if="quiz.code"
                class="mt-2 overflow-x-auto rounded-lg bg-slate-900 p-3 text-xs leading-relaxed text-emerald-300"><code>{{ quiz.code }}</code></pre>

              <p class="mt-2 text-xs text-slate-500">

                Jawaban:
                <span class="font-semibold text-emerald-600">
                  {{ quiz.answer }}
                </span>
              </p>
            </div>

            <!-- ACTION -->
            <div class="flex items-center gap-2 shrink-0">

              <button @click="editQuiz(quiz)"
                class="rounded-lg bg-slate-100 p-2 text-slate-700 transition hover:bg-slate-200">

                <Pencil class="h-3.5 w-3.5" />
              </button>

              <button @click="removeQuiz(quiz.id)"
                class="rounded-lg bg-red-500 p-2 text-white transition hover:bg-red-600">

                <Trash2 class="h-3.5 w-3.5" />
              </button>
            </div>
          </div>
        </div>

        <!-- EMPTY -->
        <div v-if="!meeting.quizzes?.length"
          class="rounded-lg border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">

          Belum ada soal quiz
        </div>
      </div>
    </div>
  </section>
</template>