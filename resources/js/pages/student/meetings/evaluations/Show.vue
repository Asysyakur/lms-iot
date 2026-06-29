<!-- resources/js/pages/student/meetings/evaluations/Show.vue -->

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { toast } from 'vue-sonner';
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { router } from '@inertiajs/vue3';

import {
  BadgeHelp,
  Info,
  SendHorizonal,
} from 'lucide-vue-next';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  meeting: any;
  evaluation: any;
  submission: any;
}>();

const answers = ref(
  props.submission?.answers
  ||
  props.evaluation?.questions?.map(
    () => ''
  )
  ||
  []
);

const submitEvaluation =
  async () => {

    const request =
      axios.post(
        `/student/meetings/${props.meeting.id}/evaluation/submit`,
        {
          answers:
            answers.value,
        }
      );

    toast.promise(request, {

      loading:
        'Mengirim evaluasi...',

      success: () => {

        router.visit(
          `/student/meetings/${props.meeting.id}`
        );

        return 'Evaluasi berhasil dikumpulkan';
      },

      error: (error: any) => {

        return (
          error.response?.data?.message
          ||
          'Gagal mengirim evaluasi'
        );
      },
    });

    await request;
  };
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <!-- TITLE -->
        <h1 class="text-2xl font-bold">

          Evaluasi Pertemuan
          {{
            props.meeting
              .meeting_number
          }}
        </h1>

        <!-- DESC -->
        <p class="mt-1 max-w-2xl text-sm text-violet-100">

          Jawab pertanyaan evaluasi
          berdasarkan materi dan praktik
          yang telah dipelajari.
        </p>
      </div>
    </section>

    <!-- QUESTION -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <!-- TOP -->
      <div class="mb-5 flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100">

          <BadgeHelp class="h-5 w-5 text-violet-600" />
        </div>

        <div>

          <h2 class="text-sm font-bold text-slate-800">

            Pertanyaan Evaluasi
          </h2>

          <p class="mt-1 text-xs text-slate-500">

            Jawab sesuai pemahamanmu.
          </p>
        </div>
      </div>

      <div class="space-y-5">

        <!-- QUESTION -->
        <div v-for="(question, index) in props.evaluation.questions" :key="index"
          class="rounded-xl border border-slate-200 p-4">

          <!-- NUMBER -->
          <h3 class="mb-3 text-sm font-semibold leading-relaxed text-slate-700">
            {{ index + 1 }}.
            {{ question }}
          </h3>

          <!-- ANSWER -->
          <textarea v-model="answers[index]" rows="5" placeholder="Tuliskan jawabanmu..."
            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
        </div>

        <!-- BUTTON -->
        <button @click="submitEvaluation"
          class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:opacity-50">

          <SendHorizonal class="h-4 w-4" />

          Kirim Evaluasi
        </button>
      </div>
    </section>
  </div>
</template>