<!-- resources/js/pages/teacher/reports/MeetingDetail.vue -->

<script setup lang="ts">
import {
  useForm,
} from '@inertiajs/vue3';

import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

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
      }),
    ),
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

    /**
     * ARRAY
     */
    if (
      Array.isArray(
        answer,
      )
    ) {

      return answer.join(
        ', ',
      );
    }

    /**
     * JSON STRING
     */
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
</script>

<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <section class="rounded-3xl bg-linear-to-r from-emerald-600 to-teal-600 p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">
            📘 {{ props.meeting.title }}
          </h1>

          <p class="mt-2 text-emerald-100">
            Monitoring aktivitas siswa
            pada
            {{ props.meeting.title }}.
          </p>
        </div>

        <div class="flex gap-3">

          <button @click="saveScores" :disabled="form.processing"
            class="rounded-2xl bg-white px-5 py-3 font-semibold text-emerald-700 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50">
            {{
              form.processing
                ? 'Menyimpan...'
                : '💾 Simpan Nilai'
            }}
          </button>

          <button class="rounded-2xl bg-white px-5 py-3 font-semibold text-emerald-700 transition hover:bg-slate-100">
            ⬇ Download Excel
          </button>

        </div>
      </div>
    </section>

    <!-- TABLE -->
    <section class="overflow-hidden rounded-3xl bg-white shadow-sm">
      <div class="overflow-x-auto">

        <table class="min-w-max text-sm">

          <thead class="bg-slate-900 text-white">
            <tr>

              <!-- NOMOR -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                No
              </th>

              <!-- NAMA -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Nama Siswa
              </th>

              <!-- PEMANTIK -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Jawaban Pemantik
              </th>

              <!-- NILAI PEMANTIK -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Nilai Pemantik
              </th>

              <!-- WAKTU AKSES -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Waktu Akses
              </th>

              <!-- REFLEKSI -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Jawaban Refleksi
              </th>

              <!-- KUIS -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Nilai Kuis
              </th>

              <!-- PRAKTIK -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Link Praktikum
              </th>

              <!-- NILAI PRAKTIK -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Nilai Praktikum
              </th>

              <!-- LKPD -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                LKPD
              </th>

              <!-- EVALUASI -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Jawaban Evaluasi
              </th>

              <!-- NILAI EVALUASI -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Nilai Evaluasi
              </th>

              <!-- TOTAL -->
              <th class="px-4 py-3 text-left whitespace-nowrap">
                Total Nilai
              </th>

            </tr>
          </thead>

          <tbody>
            <tr v-for="(
student,
  index
                            ) in props.students" :key="index"
              class="border-b border-slate-100 text-slate-700 hover:bg-slate-50">

              <!-- NOMOR -->
              <td class="px-4 py-4">
                {{ index + 1 }}
              </td>

              <!-- NAMA -->
              <td class="px-4 py-4 font-semibold whitespace-nowrap">
                {{ student.name }}
              </td>

              <!-- JAWABAN PEMANTIK -->
              <td class="min-w-60 px-4 py-4">
                {{
                  student.triggerAnswer ||
                  '-'
                }}
              </td>

              <!-- NILAI PEMANTIK -->
              <td class="px-4 py-4">
                <input type="number" min="1" max="100" v-model="form.students[index]
                  .triggerScore
                  " " class=" w-20 rounded-xl border border-slate-200 px-3 py-2 text-center" />
              </td>

              <!-- WAKTU AKSES -->
              <td class="px-4 py-4 whitespace-nowrap text-slate-500">
                {{
                  student.accessTime ||
                  '-'
                }}
              </td>

              <!-- REFLEKSI -->
              <td class="min-w-60 px-4 py-4">
                {{
                  formatAnswer(student.reflectionAnswer) ||
                  '-'
                }}
              </td>

              <!-- KUIS -->
              <td class="px-4 py-4">
                <div class="rounded-xl bg-blue-50 px-3 py-2 text-center font-bold text-blue-600">
                  {{
                    student.quiz || 0
                  }}
                </div>
              </td>

              <!-- LINK PRAKTIK -->
              <td class="px-4 py-4">
                <a v-if="
                  student.practice
                " :href="student.practice
                  " target="_blank"
                  class="rounded-full bg-blue-100 px-4 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-200">
                  Buka
                </a>

                <span v-else class="text-slate-400">
                  -
                </span>
              </td>

              <!-- NILAI PRAKTIK -->
              <td class="px-4 py-4">
                <input type="number" min="1" max="100" v-model="form.students[index]
                  .practiceScore
                  " class=" w-20 rounded-xl border border-slate-200 px-3 py-2 text-center" />
              </td>

              <!-- LKPD -->
              <td class="px-4 py-4">
                <a v-if="
                  student.lkpd
                " :href="`/storage/${student.lkpd}`" target="_blank"
                  class="rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-200">
                  Lihat
                </a>

                <span v-else class="text-slate-400">
                  -
                </span>
              </td>

              <!-- JAWABAN EVALUASI -->
              <td class="min-w-60 px-4 py-4">
                {{
                  formatAnswer(student.evaluation) ||
                  '-'
                }}
              </td>

              <!-- NILAI EVALUASI -->
              <td class="px-4 py-4">
                <input type="number" min="1" max="100" v-model="form.students[index]
                  .evaluationScore
                  " " class=" w-20 rounded-xl border border-slate-200 px-3 py-2 text-center" />
              </td>

              <!-- TOTAL -->
              <td class="px-4 py-4">
                <div class="rounded-full bg-emerald-100 px-4 py-2 text-center font-bold text-emerald-700">
                  {{
                    calculateTotal(
                      form.students[index],
                      student
                    )
                  }}
                </div>
              </td>

            </tr>
          </tbody>

        </table>

      </div>
    </section>
  </div>
</template>