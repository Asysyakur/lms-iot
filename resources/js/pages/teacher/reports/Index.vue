<!-- resources/js/pages/teacher/reports/Index.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
  layout: TeacherSidebarLayout,
});

const props = defineProps<{
  menus: any;
}>();

</script>

<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-slate-800">
            📑 Monitoring dan Laporan
          </h1>

          <p class="mt-1 text-sm text-slate-500">
            Pilih laporan yang ingin dilihat
            berdasarkan aktivitas siswa.
          </p>
        </div>

        <button
          class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">
          Download Ringkasan
        </button>
      </div>

      <!-- MENU -->
      <div class="mt-8 grid gap-5 lg:grid-cols-4">
        <Link v-for="menu in props.menus" :key="menu.id" :href="menu.href"
          class="group rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg">
          <div :class="menu.color"
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br text-lg font-bold text-white">
            {{ menu.icon }}
          </div>

          <h2 class="mt-5 font-bold text-slate-800">
            {{ menu.title }}
          </h2>

          <p class="mt-2 text-sm leading-relaxed text-slate-500">
            {{ menu.description }}
          </p>

          <div class="mt-5 flex items-center text-sm font-semibold text-emerald-600">
            Lihat Laporan →
          </div>
        </Link>
      </div>
    </section>

    <!-- STATUS -->
    <section class="grid gap-6 lg:grid-cols-2">

      <!-- PROGRESS -->
      <div class="rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">
          Status Pengumpulan Pertemuan
        </h2>

        <div class="mt-6 space-y-5">

          <div>
            <div class="mb-2 flex items-center justify-between text-sm">
              <span class="font-semibold text-slate-700">
                Pertemuan 1
              </span>

              <span class="text-emerald-600">
                96%
              </span>
            </div>

            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
              <div class="h-full w-[96%] rounded-full bg-emerald-500" />
            </div>
          </div>

          <div>
            <div class="mb-2 flex items-center justify-between text-sm">
              <span class="font-semibold text-slate-700">
                Pertemuan 2
              </span>

              <span class="text-blue-600">
                78%
              </span>
            </div>

            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
              <div class="h-full w-[78%] rounded-full bg-blue-500" />
            </div>
          </div>

          <div>
            <div class="mb-2 flex items-center justify-between text-sm">
              <span class="font-semibold text-slate-700">
                Pertemuan 3
              </span>

              <span class="text-orange-600">
                45%
              </span>
            </div>

            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
              <div class="h-full w-[45%] rounded-full bg-orange-500" />
            </div>
          </div>

        </div>
      </div>

      <!-- NOTES -->
      <div class="rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">
          Catatan Penilaian Manual
        </h2>

        <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm leading-relaxed text-slate-600">
          Nilai pemantik, praktik,
          dan evaluasi dapat diisi
          manual oleh guru dengan
          rentang 1–100. Total nilai
          dihitung otomatis dari
          nilai pemantik, refleksi,
          kuis, dan praktik.
        </div>
      </div>

    </section>

  </div>
</template>