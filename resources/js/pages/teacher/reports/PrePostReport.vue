<!-- resources/js/pages/teacher/reports/PrePostReport.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
  students: any[];
  classes: string[];
  selectedClass: string | null;
}>();

const selectedClass = ref(props.selectedClass ?? '');

const exportUrl = computed(() => {
  const params = new URLSearchParams();

  if (selectedClass.value) params.set('class', selectedClass.value);

  const query = params.toString();
  return `/teacher/reports/assessments/export${query ? `?${query}` : ''}`;
});

const filterByClass = () => {
  router.get('/teacher/reports/assessments', selectedClass.value
    ? { class: selectedClass.value }
    : {}, { preserveState: true, preserveScroll: true });
};

defineOptions({
  layout: TeacherSidebarLayout,
});
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

          <h1 class="text-lg font-bold">

            Hasil Pre-test & Post-test
          </h1>

          <p class="mt-1 text-xs text-blue-100">

            Informasi nilai pre-test dan post-test siswa.
          </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
          <select v-model="selectedClass" @change="filterByClass"
            class="rounded-lg border-0 bg-white px-3 py-2 text-sm font-medium text-slate-700 outline-none ring-1 ring-white/40">
            <option value="">Semua kelas</option>
            <option v-for="className in classes" :key="className" :value="className">
              {{ className }}
            </option>
          </select>

          <a :href="exportUrl"
            class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-slate-100">
            Download Excel{{ selectedClass ? ` — ${selectedClass}` : '' }}
          </a>
        </div>
      </div>
    </section>

    <!-- TABLE -->
    <section class="overflow-hidden rounded-xl bg-white shadow-sm">

      <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

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

                Kelas
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Pre-test
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Nilai Post-test
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Status Pre-test
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold whitespace-nowrap">

                Status Post-test
              </th>

            </tr>
          </thead>

          <!-- BODY -->
          <tbody>

            <tr v-for="student in students" :key="student.id"
              class="border-b border-slate-100 text-sm text-slate-700 hover:bg-slate-50">

              <!-- NO -->
              <td class="px-4 py-3 text-sm text-slate-500">

                {{ students.indexOf(student) + 1 }}
              </td>

              <!-- NAME -->
              <td class="px-4 py-3 text-sm font-semibold whitespace-nowrap text-slate-700">

                {{ student.name }}
              </td>

              <!-- CLASS -->
              <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-600">

                {{ student.class }}
              </td>

              <!-- PRETEST -->
              <td class="px-4 py-3">

                <div
                  class="inline-flex min-w-[48px] items-center justify-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-bold text-slate-700">

                  {{
                    student.pretest_score ?? '-'
                  }}
                </div>
              </td>

              <!-- POSTTEST -->
              <td class="px-4 py-3">

                <div
                  class="inline-flex min-w-[48px] items-center justify-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-bold text-slate-700">

                  {{
                    student.posttest_score ?? '-'
                  }}
                </div>
              </td>

              <!-- STATUS PRE -->
              <td class="px-4 py-3">

                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold" :class="student.pretest_status === 'Selesai'
                  ? 'bg-emerald-100 text-emerald-700'
                  : 'bg-slate-100 text-slate-500'
                  ">

                  {{ student.pretest_status }}
                </span>
              </td>

              <!-- STATUS POST -->
              <td class="px-4 py-3">

                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold" :class="student.posttest_status === 'Selesai'
                  ? 'bg-emerald-100 text-emerald-700'
                  : 'bg-slate-100 text-slate-500'
                  ">

                  {{ student.posttest_status }}
                </span>
              </td>

            </tr>

            <!-- EMPTY -->
            <tr v-if="!students.length">

              <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">

                Belum ada data nilai
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </section>

  </div>
</template>
