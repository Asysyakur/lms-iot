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
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section class="rounded-xl bg-linear-to-r from-emerald-600 to-teal-600 p-4 text-white shadow-sm">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

          <h1 class="text-lg font-bold">

            {{ props.meeting.title }}
          </h1>

          <p class="mt-1 text-xs text-emerald-100">

            Monitoring aktivitas siswa
            pada {{ props.meeting.title }}.
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

        <table class="min-w-max text-sm">

          <!-- HEAD -->
          <thead class="bg-slate-900 text-white">

            <tr>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                No
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nama Siswa
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Jawaban Pemantik
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Pemantik
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Waktu Akses
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Jawaban Refleksi
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Kuis
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Link Praktikum
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Praktikum
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                LKPD
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Jawaban Evaluasi
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Evaluasi
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Total Nilai
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
              <td class="px-4 py-3">

                {{ index + 1 }}
              </td>

              <!-- NAME -->
              <td class="px-4 py-3 font-semibold whitespace-nowrap">

                {{ student.name }}
              </td>

              <!-- PEMANTIK -->
              <td class="min-w-[220px] px-4 py-3 text-xs leading-relaxed">

                {{
                  student.triggerAnswer ||
                  '-'
                }}
              </td>

              <!-- SCORE PEMANTIK -->
              <td class="px-4 py-3">

                <input type="number" min="1" max="100" v-model="form.students[index].triggerScore"
                  class="w-16 rounded-lg border border-slate-200 px-2 py-1.5 text-center text-sm outline-none focus:border-emerald-500" />
              </td>

              <!-- ACCESS -->
              <td class="px-4 py-3 text-xs whitespace-nowrap text-slate-500">

                {{
                  student.accessTime ||
                  '-'
                }}
              </td>

              <!-- REFLEKSI -->
              <td class="min-w-[220px] px-4 py-3 text-xs leading-relaxed">

                {{
                  formatAnswer(
                    student.reflectionAnswer
                  ) || '-'
                }}
              </td>

              <!-- QUIZ -->
              <td class="px-4 py-3">

                <div class="rounded-lg bg-blue-50 px-3 py-2 text-center text-sm font-bold text-blue-600">

                  {{
                    student.quiz || 0
                  }}
                </div>
              </td>

              <!-- PRAKTIK -->
              <td class="px-4 py-3">

                <a v-if="student.practice" :href="student.practice" target="_blank"
                  class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-[11px] font-semibold text-blue-700 transition hover:bg-blue-200">

                  Buka
                </a>

                <span v-else class="text-slate-400">

                  -
                </span>
              </td>

              <!-- SCORE PRAKTIK -->
              <td class="px-4 py-3">

                <input type="number" min="1" max="100" v-model="form.students[index].practiceScore"
                  class="w-16 rounded-lg border border-slate-200 px-2 py-1.5 text-center text-sm outline-none focus:border-emerald-500" />
              </td>

              <!-- LKPD -->
              <td class="px-4 py-3">

                <a v-if="student.lkpd" :href="`/storage/${student.lkpd}`" target="_blank"
                  class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold text-emerald-700 transition hover:bg-emerald-200">

                  Lihat
                </a>

                <span v-else class="text-slate-400">

                  -
                </span>
              </td>

              <!-- EVALUASI -->
              <td class="min-w-[220px] px-4 py-3 text-xs leading-relaxed">

                {{
                  formatAnswer(
                    student.evaluation
                  ) || '-'
                }}
              </td>

              <!-- SCORE EVALUASI -->
              <td class="px-4 py-3">

                <input type="number" min="1" max="100" v-model="form.students[index].evaluationScore"
                  class="w-16 rounded-lg border border-slate-200 px-2 py-1.5 text-center text-sm outline-none focus:border-emerald-500" />
              </td>

              <!-- TOTAL -->
              <td class="px-4 py-3">

                <div class="rounded-full bg-emerald-100 px-3 py-1.5 text-center text-sm font-bold text-emerald-700">

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