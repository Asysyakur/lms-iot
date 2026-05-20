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
  <div class="space-y-6">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-700 to-purple-500 p-6 text-white shadow-lg">
      <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-white/10" />

      <div>
        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
          Evaluasi
        </span>

        <h1 class="mt-4 text-3xl font-bold">
          Evaluasi Pertemuan
          {{
            props.meeting
              .meeting_number
          }}
        </h1>

        <p class="mt-2 text-violet-100">
          Pertanyaan evaluasi dibuka
          dan diatur oleh guru.
        </p>
      </div>
    </section>

    <!-- INFO -->
    <section class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
      <div class="flex items-start gap-3">
        <div class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100">
          <Info class="h-4 w-4 text-blue-600" />
        </div>

        <div>
          <h3 class="font-semibold text-blue-700">
            Evaluasi berbeda dari
            refleksi.
          </h3>

          <p class="mt-1 text-sm leading-relaxed text-blue-600">
            Refleksi ada di akhir
            materi, sedangkan evaluasi
            berisi pertanyaan akhir
            pertemuan yang dibuka oleh
            guru.
          </p>
        </div>
      </div>
    </section>

    <!-- QUESTION CARD -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <div class="mb-8 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100">
          <BadgeHelp class="h-6 w-6 text-violet-600" />
        </div>

        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Pertanyaan Evaluasi
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Jawab berdasarkan praktik
            dan materi yang telah
            dipelajari.
          </p>
        </div>
      </div>

      <div class="space-y-8">

        <!-- QUESTION -->
        <div>
          <h3 class="mb-3 font-semibold text-slate-700">
            1.
            {{
              props.evaluation.question
            }}
          </h3>

          <textarea v-model="answers[0]" rows="6" placeholder="Tuliskan jawabanmu..."
            class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm outline-none transition focus:border-violet-500" />
        </div>

        <!-- BUTTON -->
        <button @click="submitEvaluation"
          class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-violet-700 to-purple-500 px-6 py-4 font-semibold text-white transition hover:opacity-90">
          <SendHorizonal class="h-5 w-5" />

          Kirim Evaluasi
        </button>
      </div>
    </section>
  </div>
</template>