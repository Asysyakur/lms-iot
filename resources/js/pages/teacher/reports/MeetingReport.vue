<!-- resources/js/pages/teacher/reports/MeetingReport.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

const props = defineProps<{
  meetings: any[];
}>();

defineOptions({
  layout: TeacherSidebarLayout,
});

const students = [
  {
    name: 'Andi Pratama',
    pemantik:
      'Microbit bisa mendeteksi cahaya.',
    pemantikScore: 80,
    accessTime: '42 menit',
    reflection:
      'Materi mudah dipahami.',
    quiz: 88,
    practice:
      'https://makecode.microbit.org',
    practiceScore: 90,
    lkpd: 'File LKPD',
    evaluation:
      'Input sensor & LED.',
    evaluationScore: 85,
  },

  {
    name: 'Siti Rahma',
    pemantik:
      'Sensor membaca intensitas.',
    pemantikScore: 75,
    accessTime: '35 menit',
    reflection:
      'Masih bingung loop.',
    quiz: 82,
    practice:
      'https://makecode.microbit.org',
    practiceScore: 84,
    lkpd: 'File LKPD',
    evaluation:
      'Program output LED.',
    evaluationScore: 80,
  },
];

const calculateTotal = (
  student: any,
) => {
  return Math.round(
    (
      student.pemantikScore +
      student.quiz +
      student.practiceScore
    ) / 3,
  );
};
</script>

<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <section class="rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">
            📘 Hasil Pertemuan
          </h1>

          <p class="mt-2 text-emerald-100">
            Monitoring hasil
            aktivitas siswa pada
            pertemuan.
          </p>
        </div>

        <button class="rounded-2xl bg-white px-5 py-3 font-semibold text-emerald-700 transition hover:bg-slate-100">
          ⬇ Download Excel
        </button>
      </div>
    </section>

    <!-- TABLE -->
    <section class="overflow-hidden rounded-3xl bg-white shadow-sm">
      <div class="overflow-x-auto">

        <table class="w-full min-w-[1100px] text-sm">

          <thead class="bg-slate-900 text-white">
            <tr>
              <th class="px-3 py-3 text-left whitespace-nowrap">
                No
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Nama
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Pemantik
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Nilai
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Akses
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Refleksi
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Kuis
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Praktik
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Nilai
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                LKPD
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Evaluasi
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Nilai
              </th>

              <th class="px-3 py-3 text-left whitespace-nowrap">
                Total
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(student, index) in students" :key="index" class="border-b border-slate-100 hover:bg-slate-50">
              <td class="px-3 py-4">
                {{ index + 1 }}
              </td>

              <td class="px-3 py-4 font-semibold whitespace-nowrap">
                {{ student.name }}
              </td>

              <td class="px-3 py-4 min-w-[180px] text-slate-600">
                {{ student.pemantik }}
              </td>

              <td class="px-3 py-4">
                <input type="number" :value="student.pemantikScore"
                  class="w-16 rounded-lg border border-slate-200 px-2 py-1 text-sm" />
              </td>

              <td class="px-3 py-4 whitespace-nowrap text-slate-500">
                {{ student.accessTime }}
              </td>

              <td class="px-3 py-4 min-w-[180px] text-slate-600">
                {{ student.reflection }}
              </td>

              <td class="px-3 py-4">
                <div class="rounded-lg bg-blue-50 px-2 py-1 text-center font-bold text-blue-600">
                  {{ student.quiz }}
                </div>
              </td>

              <td class="px-3 py-4">
                <a :href="student.practice" target="_blank"
                  class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                  Buka
                </a>
              </td>

              <td class="px-3 py-4">
                <input type="number" :value="student.practiceScore"
                  class="w-16 rounded-lg border border-slate-200 px-2 py-1 text-sm" />
              </td>

              <td class="px-3 py-4">
                <button class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                  Lihat
                </button>
              </td>

              <td class="px-3 py-4 min-w-[180px] text-slate-600">
                {{ student.evaluation }}
              </td>

              <td class="px-3 py-4">
                <input type="number" :value="student.evaluationScore"
                  class="w-16 rounded-lg border border-slate-200 px-2 py-1 text-sm" />
              </td>

              <td class="px-3 py-4">
                <div class="rounded-full bg-emerald-100 px-3 py-1 text-center font-bold text-emerald-700">
                  {{ calculateTotal(student) }}
                </div>
              </td>
            </tr>
          </tbody>

        </table>

      </div>
    </section>
  </div>
</template>