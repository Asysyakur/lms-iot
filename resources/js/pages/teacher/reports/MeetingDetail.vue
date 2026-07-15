<!-- resources/js/pages/teacher/reports/MeetingDetail.vue -->

<script setup lang="ts">
import {
  computed,
  ref,
} from 'vue';

import {
  useForm,
} from '@inertiajs/vue3';

import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

import {
  toast,
} from 'vue-sonner';

defineOptions({
  layout:
    TeacherSidebarLayout,
});

const props = defineProps<{
  meeting: any;
  students: any[];
}>();

const detailOpen = ref(false);
const selectedStudent = ref<any>(null);

const selectedAnswers = computed(() => {
  const evaluation = selectedStudent.value?.evaluation;

  if (!evaluation) {
    return [];
  }

  if (Array.isArray(evaluation)) {
    return evaluation;
  }

  try {
    const parsed = JSON.parse(evaluation);
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
});

const form = useForm({
  students:
    props.students.map(
      (
        student: any,
      ) => ({
        id:
          student.id,

        triggerScore:
          student.triggerScore
          || 0,

        practiceScore:
          student.practiceScore
          || 0,

        evaluationScore:
          student.evaluationScore
          || 0,

        feedback:
          student.feedback
          || '',
      }),
    ),
});

const selectedFormStudent = computed(() => {

  return form.students.find(
    (formStudent: any) =>
      formStudent.id ===
      selectedStudent.value?.id,
  );

});

const saveScores =
  () => {

    form.post(
      `/teacher/reports/meetings/${props.meeting.id}/scores`,
      {
        preserveScroll:
          true,

        onSuccess:
          () => {

            toast.success(
              'Nilai berhasil disimpan',
            );
          },

        onError:
          () => {

            toast.error(
              'Terjadi kesalahan saat menyimpan nilai',
            );
          },
      },
    );
  };

const calculateTotal =
  (
    formStudent: any,
    originalStudent: any,
  ) => {

    const pemantik =
      Number(
        formStudent.triggerScore,
      )
      || 0;

    const quiz =
      Number(
        originalStudent.quiz,
      )
      || 0;

    const praktik =
      Number(
        formStudent.practiceScore,
      )
      || 0;

    const evaluasi =
      Number(
        formStudent.evaluationScore,
      )
      || 0;

    return Math.round(
      (
        pemantik +
        quiz +
        praktik +
        evaluasi
      ) / 4,
    );
  };

const formatAnswer =
  (
    answer: any,
  ) => {

    if (!answer) {
      return '-';
    }

    if (
      Array.isArray(
        answer,
      )
    ) {

      return answer.join(
        ', ',
      );
    }

    try {

      const parsed =
        JSON.parse(
          answer,
        );

      if (
        Array.isArray(
          parsed,
        )
      ) {

        return parsed.join(
          ', ',
        );
      }

      return parsed;

    } catch {

      return answer;
    }
  };

const previewAnswer =
  (
    answer: any,
    charLimit = 20,
  ) => {

    const text =
      String(
        formatAnswer(
          answer,
        ),
      ).trim();

    if (!text || text === '-') {
      return '-';
    }

    if (text.length <= charLimit) {
      return text;
    }

    return `${text.slice(0, charLimit).trimEnd()}...`;
  };

const previewEvaluation =
  (
    evaluation: any,
  ) => {

    if (!evaluation) {
      return '-';
    }

    const answers =
      Array.isArray(evaluation)
        ? evaluation
        : (() => {

          try {
            const parsed = JSON.parse(evaluation);
            return Array.isArray(parsed) ? parsed : [];
          } catch {
            return [];
          }
        })();

    if (!answers.length) {
      return '-';
    }

    return previewAnswer(answers[0]);
  };

const evaluationCount =
  (
    evaluation: any,
  ) => {

    if (!evaluation) {
      return 0;
    }

    if (Array.isArray(evaluation)) {
      return evaluation.length;
    }

    try {
      const parsed = JSON.parse(evaluation);
      return Array.isArray(parsed) ? parsed.length : 0;
    } catch {
      return 0;
    }
  };

const openDetail =
  (student: any) => {
    selectedStudent.value = student;
    detailOpen.value = true;
  };
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

          <h1 class="text-lg font-bold">

            {{ props.meeting.title }}
          </h1>

          <p class="mt-1 text-xs text-emerald-100">

            Monitoring aktivitas siswa
            pada {{ props.meeting.title }}.
          </p>

          <p class="mt-1 text-xs text-emerald-100">
            Kelas: {{ props.meeting.class }}
          </p>
        </div>

        <div class="flex flex-wrap gap-2">

          <!-- SAVE -->
          <button @click="saveScores" :disabled="form.processing"
            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50">

            {{
              form.processing
                ? 'Menyimpan...'
                : 'Simpan Nilai'
            }}
          </button>

          <!-- EXPORT -->
          <a :href="`/teacher/reports/meetings/${meeting.id}/export`"
            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-slate-100">

            Download Excel
          </a>
        </div>
      </div>
    </section>

    <!-- TABLE -->
    <section class="overflow-hidden rounded-xl bg-white shadow-sm">

      <div class="overflow-x-auto">

        <table class="min-w-full table-fixed text-sm">

          <!-- HEAD -->
          <thead class="bg-slate-900 text-white">

            <tr>

              <th class="w-14 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                No
              </th>

              <th class="w-44 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nama Siswa
              </th>

              <th class="w-72 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Jawaban Pemantik
              </th>

              <th class="w-24 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Pemantik
              </th>

              <th class="w-32 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Waktu Akses
              </th>

              <th class="w-72 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Jawaban Refleksi
              </th>

              <th class="w-24 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Kuis
              </th>

              <th class="w-72 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">
                Praktikum
              </th>

              <th class="w-24 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Praktikum
              </th>

              <th class="w-24 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                LKPD
              </th>

              <th class="w-48 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Jawaban Evaluasi
              </th>

              <th class="w-24 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Evaluasi
              </th>

              <th class="w-28 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Total Nilai
              </th>

              <th class="w-24 px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Detail
              </th>
            </tr>
          </thead>

          <!-- BODY -->
          <tbody>

            <tr v-for="(
student,
  index
              ) in props.students" :key="index"
              class="border-b border-slate-100 text-sm text-slate-700 hover:bg-slate-50">

              <!-- NO -->
              <td class="px-4 py-3 text-center">

                {{ index + 1 }}
              </td>

              <!-- NAME -->
              <td class="px-4 py-3 font-semibold whitespace-nowrap">

                {{ student.name }}
              </td>

              <!-- PEMANTIK -->
              <td class="px-4 py-3 align-middle">

                <div
                  class="flex w-full min-w-0 items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs leading-5 text-slate-700">
                  <span class="block min-w-0 truncate">
                    {{
                      previewAnswer(
                        student.triggerAnswer,
                      )
                    }}
                  </span>
                </div>
              </td>

              <!-- SCORE PEMANTIK -->
              <td class="px-4 py-3">

                <input type="number" min="1" max="100" v-model="form.students[index].triggerScore"
                  class="w-14 rounded-lg border border-slate-200 px-2 py-1 text-center text-sm outline-none focus:border-emerald-500" />
              </td>

              <!-- ACCESS -->
              <td class="px-4 py-3 text-center text-xs whitespace-nowrap text-slate-500">

                {{
                  student.accessTime ||
                  '-'
                }}
              </td>

              <!-- REFLEKSI -->
              <td class="px-4 py-3 align-middle">

                <div
                  class="flex w-full min-w-0 items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs leading-5 text-slate-700">
                  <span class="block min-w-0 truncate">
                    {{
                      previewAnswer(
                        student.reflectionAnswer
                      )
                    }}
                  </span>
                </div>
              </td>

              <!-- QUIZ -->
              <td class="px-4 py-3 text-center">

                <div
                  class="inline-flex min-w-12 items-center justify-center rounded-full bg-slate-100 px-3 py-1.5 text-sm font-bold text-slate-700">

                  {{
                    student.quiz || 0
                  }}
                </div>
              </td>

              <!-- PRAKTIK -->
              <td class="px-4 py-3 text-center align-middle">

                <!-- TYPE TEXT -->
                <div v-if="meeting.practiceType === 'text'"
                  class="flex w-full min-w-0 items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs leading-5 text-slate-700">
                  <span class="block min-w-0 truncate">{{ previewAnswer(student.practiceText) }}</span>
                </div>

                <!-- TYPE LINK -->
                <a v-else-if="student.practice" :href="student.practice" target="_blank"
                  class="inline-flex max-w-fit whitespace-nowrap rounded-full bg-blue-100 px-3 py-2 text-[11px] font-semibold leading-none text-blue-700">
                  Buka Link
                </a>

                <!-- EMPTY -->
                <span v-else class="text-slate-400">
                  -
                </span>

              </td>

              <!-- SCORE PRAKTIK -->
              <td class="px-4 py-3 text-center">

                <input type="number" min="1" max="100" v-model="form.students[index].practiceScore"
                  class="w-14 rounded-lg border border-slate-200 px-2 py-1 text-center text-sm outline-none focus:border-emerald-500" />
              </td>

              <!-- LKPD -->
              <td class="px-4 py-3 text-center">

                <a v-if="student.lkpd" :href="`/storage/${student.lkpd}`" target="_blank"
                  class="inline-flex rounded-full bg-emerald-100 px-3 py-2 text-[11px] font-semibold text-emerald-700 transition hover:bg-emerald-200">

                  Lihat
                </a>

                <span v-else class="text-slate-400">

                  -
                </span>
              </td>

              <!-- EVALUASI -->
              <td class="px-4 py-3 text-center align-center">

                <div v-if="!student.evaluation" class="text-xs text-slate-400">
                  -
                </div>

                <div v-else class="flex w-full min-w-0 flex-col gap-2">
                  <div
                    class="flex w-full min-w-0 items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs leading-5 text-slate-700">
                    <span class="block min-w-0 truncate">{{ previewEvaluation(student.evaluation) }}</span>
                  </div>
                </div>
              </td>

              <!-- SCORE EVALUASI -->
              <td class="px-4 py-3 text-center">

                <input type="number" min="1" max="100" v-model="form.students[index].evaluationScore"
                  class="w-14 rounded-lg border border-slate-200 px-2 py-1 text-center text-sm outline-none focus:border-emerald-500" />
              </td>

              <!-- TOTAL -->
              <td class="px-4 py-3 text-center">

                <div
                  class="inline-flex min-w-12 items-center justify-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-bold text-emerald-700">

                  {{
                    calculateTotal(
                      form.students[index],
                      student
                    )
                  }}
                </div>
              </td>

              <!-- DETAIL -->
              <td class="px-4 py-3 text-center">

                <button @click="openDetail(student)"
                  class="inline-flex rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                  Detail
                </button>
              </td>

            </tr>

          </tbody>
        </table>
      </div>
    </section>

    <Dialog :open="detailOpen" @update:open="detailOpen = $event">
      <DialogContent class="!gap-0 max-h-[92vh] max-w-5xl overflow-hidden rounded-2xl border-0 p-0">

        <!-- Header -->
        <div class="bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">
          <DialogHeader class="space-y-1">

            <DialogTitle class="text-2xl font-bold">
              Detail Monitoring Siswa
            </DialogTitle>

            <DialogDescription class="text-emerald-100">
              {{ selectedStudent?.name }} - {{ meeting.title }}
            </DialogDescription>

          </DialogHeader>

        </div>

        <div v-if="selectedStudent" class="max-h-[75vh] space-y-6 overflow-y-auto bg-slate-100 p-6">

          <!-- Ringkasan -->
          <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-4">

            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                Nama
              </p>

              <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ selectedStudent.name }}
              </p>
            </div>

            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                Kelas
              </p>

              <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ selectedStudent.class || '-' }}
              </p>
            </div>

            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                Waktu Akses
              </p>

              <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ selectedStudent.accessTime || '-' }}
              </p>
            </div>

            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                Nilai Quiz
              </p>

              <span class="mt-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-bold text-blue-700">

                {{ selectedStudent.quiz || 0 }}

              </span>
            </div>

            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                Total Nilai
              </p>

              <span class="mt-2 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-700">

                {{
                  calculateTotal(
                    form.students.find(
                      s => s.id === selectedStudent.id
                    ),
                    selectedStudent
                  )
                }}

              </span>
            </div>

          </div>

          <!-- Pemantik -->
          <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b bg-slate-50 px-5 py-3">

              <h3 class="font-semibold text-slate-800">
                Jawaban Pemantik
              </h3>

            </div>

            <div class="p-5">

              <p class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">
                {{ formatAnswer(selectedStudent.triggerAnswer) || '-' }}
              </p>

            </div>

          </section>

          <!-- Refleksi -->
          <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b bg-slate-50 px-5 py-3">

              <h3 class="font-semibold text-slate-800">
                Jawaban Refleksi
              </h3>

            </div>

            <div class="p-5">

              <p class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">
                {{ formatAnswer(selectedStudent.reflectionAnswer) || '-' }}
              </p>

            </div>

          </section>

          <!-- Praktikum -->
          <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b bg-slate-50 px-5 py-3">

              <h3 class="font-semibold text-slate-800">
                Praktikum
              </h3>

            </div>

            <div class="p-5">

              <template v-if="meeting.practiceType === 'text'">

                <p class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">
                  {{ selectedStudent.practiceText || '-' }}
                </p>

              </template>

              <template v-else>

                <a v-if="selectedStudent.practice" :href="selectedStudent.practice" target="_blank"
                  class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">

                  🔗 Buka Link Praktikum

                </a>

                <span v-else class="text-sm text-slate-400">
                  -
                </span>

              </template>

            </div>

          </section>

          <!-- LKPD -->
          <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b bg-slate-50 px-5 py-3">

              <h3 class="font-semibold text-slate-800">
                LKPD
              </h3>

            </div>

            <div class="p-5">

              <a v-if="selectedStudent.lkpd" :href="`/storage/${selectedStudent.lkpd}`" target="_blank"
                class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">

                📄 Lihat LKPD

              </a>

              <span v-else class="text-sm text-slate-400">

                Belum mengumpulkan

              </span>

            </div>

          </section>

          <!-- Evaluasi -->
          <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b bg-slate-50 px-5 py-3">

              <h3 class="font-semibold text-slate-800">
                Jawaban Evaluasi
              </h3>

            </div>

            <div v-if="selectedAnswers.length" class="space-y-4 p-5">

              <div v-for="(answer, index) in selectedAnswers" :key="index"
                class="overflow-hidden rounded-xl border border-slate-200">

                <div class="bg-violet-50 px-4 py-2 font-semibold text-violet-700">

                  Soal {{ index + 1 }}

                </div>

                <div class="bg-white p-4">

                  <p class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">
                    {{ answer }}
                  </p>

                </div>

              </div>

            </div>

            <div v-else class="p-5 text-sm text-slate-400">

              Belum ada jawaban evaluasi.

            </div>

          </section>

          <!-- Feedback -->
          <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b bg-slate-50 px-5 py-3">

              <h3 class="font-semibold text-slate-800">
                Feedback untuk Siswa
              </h3>

            </div>

            <div v-if="selectedFormStudent" class="p-5">

              <textarea v-model="selectedFormStudent.feedback" rows="4"
                placeholder="Tulis catatan atau masukan untuk siswa pada pertemuan ini..."
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />

              <p class="mt-2 text-xs text-slate-400">
                Feedback akan tampil di halaman overview pertemuan siswa. Klik
                "Simpan Nilai" untuk menyimpan.
              </p>

            </div>

          </section>

        </div>

      </DialogContent>
    </Dialog>
  </div>
</template>
