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

const showMeetingModal = ref(false);
const meetingSlider = ref<HTMLElement | null>(null);

const meetingForm = useForm({
  id: null,
  title: '',
  description: '',
  meeting_number: 1,
});

const createMeeting = async () => {

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
        'Pertemuan berhasil diupdate'
      );

      resetMeetingForm();

      showMeetingModal.value =
        false;

    } catch (error) {

      toast.error(
        'Gagal mengupdate pertemuan'
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
      'Pertemuan berhasil dibuat'
    );

    resetMeetingForm();

    showMeetingModal.value =
      false;

  } catch (error) {

    toast.error(
      'Gagal membuat pertemuan'
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

const slideMeetings = (direction: 'left' | 'right') => {
  const slider = meetingSlider.value;

  if (!slider) {
    return;
  }

  const slideDistance = slider.clientWidth * 0.85;

  slider.scrollBy({
    left: direction === 'left' ? -slideDistance : slideDistance,
    behavior: 'smooth',
  });
};

const resetMeetingForm = () => {

  meetingForm.reset();

  meetingForm.id = null;

  meetingForm.meeting_number = 1;

  meetingForm.description = '';
};
</script>

<template>
  <div class="max-w-full min-w-0 space-y-6">
    <!-- HEADER -->
    <section class="max-w-full min-w-0 rounded-3xl bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
          <h1 class="text-2xl font-bold text-slate-800">
            📚 Manajemen Pertemuan
          </h1>

          <p class="mt-1 text-sm text-slate-500">
            Kelola materi, kuis, praktik, LKPD, dan evaluasi.
          </p>
        </div>

        <button @click="showMeetingModal = true"
          class="shrink-0 cursor-pointer rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-white transition hover:bg-emerald-600">
          + Tambah Pertemuan
        </button>
      </div>

      <!-- MEETING SLIDER -->
      <div v-if="meetings.length" class="mt-6 max-w-full min-w-0">
        <!-- CONTROL -->
        <div class="mb-4 flex justify-end gap-2">
          <button type="button" @click="slideMeetings('left')"
            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 transition hover:border-emerald-300 hover:text-emerald-600">
            <ChevronLeft class="h-5 w-5" />
          </button>

          <button type="button" @click="slideMeetings('right')"
            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 transition hover:border-emerald-300 hover:text-emerald-600">
            <ChevronRight class="h-5 w-5" />
          </button>
        </div>

        <!-- SLIDER -->
        <div class="relative max-w-full min-w-0 overflow-hidden">
          <div ref="meetingSlider"
            class="flex max-w-full snap-x snap-mandatory scrollbar-none gap-5 overflow-x-auto scroll-smooth pb-2 [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <MeetingCard v-for="meeting in meetings" :key="meeting.id" :meeting="meeting"
              :active="activeMeeting?.id === meeting.id" @click="activeMeeting = meeting" @edit="editMeeting(meeting)"
              @delete="removeMeeting(meeting)"
              class="w-65 flex-[0_0_260px] snap-start sm:w-[320px] sm:flex-[0_0_320px]" />
          </div>
        </div>
      </div>
    </section>

    <!-- CONTENT -->
    <div v-if="activeMeeting" class="max-w-full min-w-0 space-y-6">
      <MaterialTab :meeting="activeMeeting" />

      <QuizTab :meeting="activeMeeting" />

      <PracticeTab :meeting="activeMeeting" />

      <LkpdTab :meeting="activeMeeting" />

      <EvaluationTab :meeting="activeMeeting" />
    </div>
    <div v-else class="rounded-3xl bg-white p-16 text-center shadow-sm">
      <h2 class="text-2xl font-bold text-slate-800">
        Belum Ada Pertemuan
      </h2>

      <p class="mt-2 text-slate-500">
        Tambahkan pertemuan pertama untuk mulai mengelola materi.
      </p>
    </div>
  </div>
  <!-- MODAL -->
  <div v-if="showMeetingModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl">
      <div class="flex items-start justify-between">
        <div>
          <h2 class="text-2xl font-bold text-slate-800">
            {{
              meetingForm.id
                ? 'Edit Pertemuan'
                : 'Tambah Pertemuan'
            }}
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Buat pertemuan baru untuk materi pembelajaran.
          </p>
        </div>

        <button @click="showMeetingModal = false"
          class="cursor-pointer text-2xl text-slate-400 transition hover:text-slate-600">
          ×
        </button>
      </div>

      <div class="mt-8 space-y-5">
        <!-- NUMBER -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Nomor Pertemuan
          </label>

          <input v-model="meetingForm.meeting_number" type="number" min="1" placeholder="Contoh: 1"
            class="w-full rounded-2xl border border-slate-200 px-4 py-3 transition outline-none focus:border-emerald-500" />
        </div>

        <!-- TITLE -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Judul Pertemuan
          </label>

          <input v-model="meetingForm.title" type="text" placeholder="Contoh: Pengenalan Micro:bit"
            class="w-full rounded-2xl border border-slate-200 px-4 py-3 transition outline-none focus:border-emerald-500" />
        </div>

        <!-- DESCRIPTION -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700">
            Deskripsi
          </label>

          <textarea v-model="meetingForm.description" placeholder="Contoh: Materi pengenalan tentang Micro:bit"
            class="w-full rounded-2xl border border-slate-200 px-4 py-3 transition outline-none focus:border-emerald-500" />
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3 pt-4">
          <button @click="showMeetingModal = false" type="button"
            class="cursor-pointer rounded-2xl border border-slate-200 px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-100">
            Batal
          </button>

          <button @click="createMeeting" type="button" :disabled="meetingForm.processing"
            class="cursor-pointer rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-50">
            {{
              meetingForm.id
                ? 'Update Pertemuan'
                : 'Simpan Pertemuan'
            }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
