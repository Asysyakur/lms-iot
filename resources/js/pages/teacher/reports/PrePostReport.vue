<!-- resources/js/pages/teacher/reports/PrePostReport.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

const props = defineProps<{
  students: any[];
}>();

defineOptions({
  layout: TeacherSidebarLayout,
});

</script>

<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <section class="rounded-3xl bg-linear-to-r from-blue-600 to-cyan-600 p-6 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">
            📄 Hasil Pre-test &
            Post-test
          </h1>

          <p class="mt-2 text-blue-100">
            Tabel informasi nilai
            pre-test dan post-test
            siswa.
          </p>
        </div>

        <a href="/teacher/reports/assessments/export"
          class="rounded-2xl bg-white px-5 py-3 font-semibold text-blue-700 transition hover:bg-slate-100">
          ⬇ Download Excel
        </a>
      </div>
    </section>

    <!-- TABLE -->
    <section class="overflow-hidden rounded-3xl bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-slate-900 text-white">
            <tr>
              <th class="px-5 py-4 text-left text-sm">
                No
              </th>

              <th class="px-5 py-4 text-left text-sm">
                Nama Siswa
              </th>

              <th class="px-5 py-4 text-left text-sm">
                Nilai Pre-test
              </th>

              <th class="px-5 py-4 text-left text-sm">
                Nilai Post-test
              </th>

              <th class="px-5 py-4 text-left text-sm">
                Status Pre-test
              </th>

              <th class="px-5 py-4 text-left text-sm">
                Status Post-test
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="student in students" :key="student.id" class="border-b border-slate-100">
              <td class="px-5 py-4 text-sm text-slate-600">
                {{ students.indexOf(student) + 1 }}
              </td>

              <td class="px-5 py-4 font-semibold text-slate-700">
                {{ student.name }}
              </td>

              <td class="px-5 py-4 text-slate-600">
                {{ student.pretest_score ?? '-' }}
              </td>

              <td class="px-5 py-4 font-bold text-emerald-600">
                {{ student.posttest_score ?? '-' }}
              </td>
              <td class="px-5 py-4">
                <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="student.pretest_status === 'Selesai'
                  ? 'bg-emerald-100 text-emerald-700'
                  : 'bg-slate-100 text-slate-500'
                  ">
                  {{ student.pretest_status }}
                </span>
              </td>
              <td class="px-5 py-4">
                <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="student.posttest_status === 'Selesai'
                  ? 'bg-emerald-100 text-emerald-700'
                  : 'bg-slate-100 text-slate-500'
                  ">
                  {{ student.posttest_status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>
</template>