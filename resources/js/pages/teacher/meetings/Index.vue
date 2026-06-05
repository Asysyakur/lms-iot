<!-- resources/js/pages/teacher/meetings/Index.vue -->

<script setup lang="ts">
import { ref } from 'vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import MeetingCard from './components/MeetingCard.vue';

import MaterialTab from './components/MaterialTab.vue';
import QuizTab from './components/QuizTab.vue';
import PracticeTab from './components/PracticeTab.vue';
import LkpdTab from './components/LkpdTab.vue';
import EvaluationTab from './components/EvaluationTab.vue';

import { useForm } from '@inertiajs/vue3';

import Swal from 'sweetalert2';

import { toast } from 'vue-sonner';

import axios from 'axios';

defineOptions({
  layout: TeacherSidebarLayout,
});

const props = defineProps<{
  meetings: any[];
}>();

const meetings = ref(
  props.meetings,
);

const activeMeeting = ref(
  meetings.value[0] ?? null,
);

const showMeetingModal =
  ref(false);

const meetingSlider =
  ref<HTMLElement | null>(null);

const meetingForm = useForm({
  id: null,

  title: '',

  description: '',

  meeting_number: 1,
});

const createMeeting =
  async () => {

    /**
     * UPDATE
     */
    if (meetingForm.id) {

      try {

        const response =
          await axios.put(
            `/teacher/meetings/${meetingForm.id}`,
            meetingForm,
          );

        const index =
          meetings.value.findIndex(
            (meeting: any) =>
              meeting.id ===
              meetingForm.id,
          );

        if (index !== -1) {

          meetings.value[index] =
            response.data.meeting;
        }

        toast.success(
          'Pertemuan berhasil diupdate',
        );

        resetMeetingForm();

        showMeetingModal.value =
          false;

      } catch (error) {

        toast.error(
          'Gagal mengupdate pertemuan',
        );
      }

      return;
    }

    /**
     * CREATE
     */
    try {

      const response =
        await axios.post(
          '/teacher/meetings',
          meetingForm,
        );

      meetings.value.push(
        response.data.meeting,
      );

      toast.success(
        'Pertemuan berhasil dibuat',
      );

      resetMeetingForm();

      showMeetingModal.value =
        false;

    } catch (error) {

      toast.error(
        'Gagal membuat pertemuan',
      );
    }
  };

const editMeeting = (
  meeting: any,
) => {

  meetingForm.id =
    meeting.id;

  meetingForm.title =
    meeting.title;

  meetingForm.meeting_number =
    meeting.meeting_number;

  meetingForm.description =
    meeting.description;

  showMeetingModal.value =
    true;
};

const removeMeeting =
  async (meeting: any) => {

    const result =
      await Swal.fire({
        title:
          'Hapus pertemuan?',

        text:
          'Semua data meeting akan ikut terhapus',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText:
          'Hapus',
      });

    if (!result.isConfirmed) {
      return;
    }

    try {

      await axios.delete(
        `/teacher/meetings/${meeting.id}`
      );

      meetings.value =
        meetings.value.filter(
          (item: any) =>
            item.id !==
            meeting.id,
        );

      if (
        activeMeeting.value?.id ===
        meeting.id
      ) {

        activeMeeting.value =
          meetings.value[0] ??
          null;
      }

      toast.success(
        'Pertemuan berhasil dihapus'
      );

    } catch (error) {

      toast.error(
        'Gagal menghapus pertemuan'
      );
    }
  };

const slideMeetings = (
  direction: 'left' | 'right'
) => {

  const slider =
    meetingSlider.value;

  if (!slider) {
    return;
  }

  const slideDistance =
    slider.clientWidth * 0.7;

  slider.scrollBy({
    left:
      direction === 'left'
        ? -slideDistance
        : slideDistance,

    behavior: 'smooth',
  });
};

const resetMeetingForm =
  () => {

    meetingForm.reset();

    meetingForm.id =
      null;

    meetingForm.meeting_number =
      1;

    meetingForm.description =
      '';
  };
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <!-- LEFT -->
        <div>

          <h1 class="text-2xl font-bold text-slate-800">

            Manajemen Pertemuan
          </h1>

          <p class="mt-1 text-sm text-slate-500">

            Kelola materi, kuis,
            praktik, LKPD,
            dan evaluasi pembelajaran.
          </p>
        </div>

        <!-- BUTTON -->
        <button @click="showMeetingModal = true"
          class="rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600">

          + Tambah Pertemuan
        </button>
      </div>

      <!-- SLIDER -->
      <div v-if="meetings.length" class="mt-5">

        <!-- CONTROL -->
        <div class="mb-3 flex justify-end gap-2">

          <button type="button" @click="slideMeetings('left')"
            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-emerald-300 hover:text-emerald-600">

            <ChevronLeft class="h-4 w-4" />
          </button>

          <button type="button" @click="slideMeetings('right')"
            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-emerald-300 hover:text-emerald-600">

            <ChevronRight class="h-4 w-4" />
          </button>
        </div>

        <!-- CARDS -->
        <div class="overflow-hidden">

          <div ref="meetingSlider"
            class="flex gap-3 overflow-x-auto scroll-smooth pb-2 [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

            <MeetingCard v-for="meeting in meetings" :key="meeting.id" :meeting="meeting" :active="activeMeeting?.id ===
              meeting.id
              " @click="
                activeMeeting = meeting
                " @edit="
                editMeeting(meeting)
                " @delete="
                removeMeeting(meeting)
                " class="flex-[0_0_220px]" />
          </div>
        </div>
      </div>
    </section>

    <!-- CONTENT -->
    <div v-if="activeMeeting" class="space-y-4">

      <MaterialTab :meeting="activeMeeting" />

      <QuizTab :meeting="activeMeeting" />

      <PracticeTab :meeting="activeMeeting" />

      <LkpdTab :meeting="activeMeeting" />

      <EvaluationTab :meeting="activeMeeting" />
    </div>

    <!-- EMPTY -->
    <div v-else class="rounded-xl bg-white p-8 text-center shadow-sm">

      <h2 class="text-lg font-bold text-slate-800">

        Belum Ada Pertemuan
      </h2>

      <p class="mt-2 text-sm text-slate-500">

        Tambahkan pertemuan
        pertama untuk mulai
        mengelola pembelajaran.
      </p>
    </div>
  </div>

  <!-- MODAL -->
  <div v-if="showMeetingModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl">

      <!-- HEADER -->
      <div class="flex items-start justify-between">

        <div>

          <h2 class="text-lg font-bold text-slate-800">

            {{
              meetingForm.id
                ? 'Edit Pertemuan'
                : 'Tambah Pertemuan'
            }}
          </h2>

          <p class="mt-1 text-sm text-slate-500">

            Atur informasi pertemuan.
          </p>
        </div>

        <button @click="
          showMeetingModal = false
          " class="text-lg text-slate-400 transition hover:text-slate-600">

          ×
        </button>
      </div>

      <!-- FORM -->
      <div class="mt-5 space-y-4">

        <!-- NUMBER -->
        <div>

          <label class="mb-2 block text-sm font-semibold text-slate-700">

            Nomor Pertemuan
          </label>

          <input v-model="meetingForm.meeting_number
            " type="number" min="1" placeholder="Contoh: 1"
            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />
        </div>

        <!-- TITLE -->
        <div>

          <label class="mb-2 block text-sm font-semibold text-slate-700">

            Judul Pertemuan
          </label>

          <input v-model="meetingForm.title
            " type="text" placeholder="Contoh: Pengenalan Micro:bit"
            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />
        </div>

        <!-- DESCRIPTION -->
        <div>

          <label class="mb-2 block text-sm font-semibold text-slate-700">

            Deskripsi
          </label>

          <textarea v-model="meetingForm.description
            " rows="4" placeholder="Contoh: Materi pengenalan tentang Micro:bit"
            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3 pt-2">

          <button @click="
            showMeetingModal = false
            " type="button"
            class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">

            Batal
          </button>

          <button @click="createMeeting" type="button" :disabled="meetingForm.processing
            "
            class="rounded-lg bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-50">

            {{
              meetingForm.id
                ? 'Update'
                : 'Simpan'
            }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>