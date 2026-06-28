<!-- resources/js/pages/teacher/reports/Index.vue -->

<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
  layout: TeacherSidebarLayout,
});

const props = defineProps<{
  menus: any;
  meetingProgress: any[];
}>();
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <h1 class="text-2xl font-bold">
          Manajemen Pertemuan
        </h1>
        <!-- BUTTON -->
        <a href="/teacher/reports/export" target="_blank"
          class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">

          Download Ringkasan
        </a>
      </div>
    </section>
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <!-- MENU -->
      <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">

        <Link v-for="menu in props.menus" :key="menu.id" :href="menu.href"
          class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">

          <!-- ICON -->
          <div :class="menu.color"
            class="flex h-9 w-9 items-center justify-center rounded-lg bg-linear-to-br text-sm font-bold text-white">

            {{ menu.icon }}
          </div>

          <!-- TITLE -->
          <h2 class="mt-3 text-sm font-bold text-slate-800">

            {{ menu.title }}
          </h2>

          <!-- DESCRIPTION -->
          <p class="mt-1.5 text-[11px] leading-relaxed text-slate-500">

            {{ menu.description }}
          </p>

          <!-- ACTION -->
          <div class="mt-3 text-xs font-semibold text-emerald-600">

            Lihat Laporan →
          </div>
        </Link>
      </div>
    </section>

    <!-- STATUS -->
    <section class="flex flex-col ">

      <!-- PROGRESS -->
      <div class="rounded-xl bg-white p-4 shadow-sm">

        <h2 class="text-sm font-bold text-slate-800">

          Status Pengumpulan Pertemuan
        </h2>

        <div class="mt-4 space-y-4">

          <div v-for="meeting in props.meetingProgress" :key="meeting.id">

            <!-- TOP -->
            <div class="mb-2 flex items-center justify-between text-sm">

              <span class="text-sm font-semibold text-slate-700">

                {{ meeting.title }}
              </span>

              <span class="text-[11px] font-semibold text-emerald-500">

                {{ meeting.percentage }}%
              </span>
            </div>

            <!-- BAR -->
            <div class="h-2 overflow-hidden rounded-full bg-slate-100">

              <div class="h-full rounded-full transition-all duration-500 bg-emerald-500" :style="{
                width:
                  meeting.percentage + '%'
              }" />
            </div>
          </div>

          <!-- EMPTY -->
          <div v-if="!props.meetingProgress.length"
            class="rounded-xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">

            Belum ada progress pertemuan
          </div>
        </div>
      </div>
    </section>

  </div>
</template>