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
  props.submission?.answers || ['']
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
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-violet-700 to-purple-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <!-- BADGE -->
        <span class="rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-semibold">

          Evaluasi
        </span>

        <!-- TITLE -->
        <h1 class="mt-3 text-2xl font-bold">

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

    <!-- INFO -->
    <section class="rounded-xl border border-blue-200 bg-blue-50 p-4">

      <div class="flex items-start gap-3">

        <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100">

          <Info class="h-4 w-4 text-blue-600" />
        </div>

        <div>

          <h3 class="text-sm font-bold text-blue-700">

            Informasi Evaluasi
          </h3>

          <p class="mt-1 text-sm leading-relaxed text-blue-600">

            Evaluasi berbeda dari
            refleksi. Evaluasi berisi
            pertanyaan akhir pertemuan
            yang dibuka oleh guru.
          </p>
        </div>
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
        <div>

          <h3 class="mb-3 text-sm font-semibold leading-relaxed text-slate-700">

            1.
            {{
              props.evaluation.question
            }}
          </h3>

          <textarea v-model="answers[0]" rows="6" placeholder="Tuliskan jawabanmu..."
            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-violet-500" />
        </div>

        <!-- BUTTON -->
        <button @click="submitEvaluation"
          class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-violet-700 to-purple-500 px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">

          <SendHorizonal class="h-4 w-4" />

          Kirim Evaluasi
        </button>
      </div>
    </section>
  </div>
</template>