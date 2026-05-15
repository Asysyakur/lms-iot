<script setup lang="ts">
import { ref } from 'vue';

import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import MeetingCard from './components/MeetingCard.vue';

import MaterialTab from './components/MaterialTab.vue';
import QuizTab from './components/QuizTab.vue';
import PracticeTab from './components/PracticeTab.vue';
import LkpdTab from './components/LkpdTab.vue';
import EvaluationTab from './components/EvaluationTab.vue';

defineOptions({
  layout: TeacherSidebarLayout,
});

const activeMeeting = ref(1);

const activeTab = ref('material');

const meetings = [
  {
    id: 1,
    title: 'Pengenalan Micro:bit',
    status: 'Selesai',
  },

  {
    id: 2,
    title: 'Sensor dan Input',
    status: 'Aktif',
  },

  {
    id: 3,
    title: 'Output LED & Buzzer',
    status: 'Draft',
  },
];
</script>

<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">

        <div>
          <h1 class="text-2xl font-bold text-slate-800">
            📚 Manajemen Pertemuan
          </h1>

          <p class="mt-1 text-sm text-slate-500">
            Kelola materi, kuis, praktik, LKPD, dan evaluasi.
          </p>
        </div>

        <button class="rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-white hover:bg-emerald-600">
          + Tambah Pertemuan
        </button>

      </div>

      <!-- MEETING -->
      <div class="mt-6 grid gap-5 lg:grid-cols-3">

        <MeetingCard v-for="meeting in meetings" :key="meeting.id" :meeting="meeting"
          :active="activeMeeting === meeting.id" @click="activeMeeting = meeting.id" />

      </div>
    </section>

    <!-- CONTENT -->
    <div class="space-y-6">

      <MaterialTab />

      <QuizTab />

      <PracticeTab />

      <LkpdTab />

      <EvaluationTab />

    </div>

  </div>
</template>