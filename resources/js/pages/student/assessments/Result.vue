<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps({
  assessment: Object,
  result: Object,
});

const getOptionText = (
  question,
  option,
) => {
  return question[
    `option_${option.toLowerCase()}`
  ];
};
</script>

<template>
  <div class="space-y-4">

    <!-- HEADER -->
    <div class="rounded-2xl bg-[#173B74] p-5 text-white shadow-lg">

      <h1 class="text-2xl font-bold">
        Assessment Selesai
      </h1>

      <p class="mt-1 text-sm text-slate-300">
        Berikut hasil pengerjaan
        {{ assessment.title }}
      </p>

      <!-- STATS -->
      <div class="mt-5 grid gap-3 md:grid-cols-3">

        <!-- SCORE -->
        <div class="rounded-xl bg-white/10 p-4 backdrop-blur-sm">

          <p class="text-xs text-slate-300">
            Score
          </p>

          <h2 class="mt-1 text-4xl font-bold">
            {{ result.score }}
          </h2>
        </div>

        <!-- CORRECT -->
        <div class="rounded-xl bg-emerald-500/20 p-4">

          <p class="text-xs text-emerald-100">
            Jawaban Benar
          </p>

          <h2 class="mt-1 text-4xl font-bold text-emerald-300">
            {{ result.correct_answers }}
          </h2>
        </div>

        <!-- WRONG -->
        <div class="rounded-xl bg-red-500/20 p-4">

          <p class="text-xs text-red-100">
            Jawaban Salah
          </p>

          <h2 class="mt-1 text-4xl font-bold text-red-300">
            {{ result.wrong_answers }}
          </h2>
        </div>
      </div>
    </div>

    <!-- REVIEW -->
    <div class="space-y-4">

      <div v-for="(answer, index) in result.answers" :key="answer.id"
        class="overflow-hidden rounded-2xl border bg-white shadow-sm" :class="answer.is_correct
            ? 'border-emerald-300'
            : 'border-red-300'
          ">

        <!-- TOP -->
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">

          <h2 class="text-sm font-bold text-slate-800">
            Soal {{ index + 1 }}
          </h2>

          <div class="rounded-full px-2.5 py-1 text-[10px] font-bold" :class="answer.is_correct
              ? 'bg-emerald-100 text-emerald-700'
              : 'bg-red-100 text-red-700'
            ">

            {{
              answer.is_correct
                ? 'Benar'
                : 'Salah'
            }}
          </div>
        </div>

        <!-- CONTENT -->
        <div class="p-4">

          <!-- QUESTION -->
          <h3 class="text-[15px] font-semibold text-slate-800">
            {{
              answer.question.question
            }}
          </h3>

          <!-- ANSWERS -->
          <div class="mt-4 grid gap-3 md:grid-cols-2">

            <!-- USER -->
            <div :class="answer.is_correct
                ? 'border-emerald-200 bg-emerald-50'
                : 'border-red-200 bg-red-50'
              " class="rounded-xl border p-3">

              <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">
                Jawaban Siswa
              </p>

              <h4 class="mt-1 text-sm font-semibold" :class="answer.is_correct
                  ? 'text-emerald-700'
                  : 'text-red-700'
                ">

                {{ answer.answer }}.
                {{
                  getOptionText(
                    answer.question,
                    answer.answer
                  )
                }}
              </h4>
            </div>

            <!-- CORRECT -->
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-3">

              <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">
                Jawaban Benar
              </p>

              <h4 class="mt-1 text-sm font-semibold text-blue-700">

                {{
                  answer.question.answer
                }}.

                {{
                  getOptionText(
                    answer.question,
                    answer.question.answer
                  )
                }}
              </h4>
            </div>
          </div>

          <!-- EXPLANATION -->
          <!-- <div class="mt-4 rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
            {{
              answer.question.explanation ??
              'Pelajari kembali materi terkait soal ini.'
            }}
          </div> -->
        </div>
      </div>
    </div>
  </div>
</template>