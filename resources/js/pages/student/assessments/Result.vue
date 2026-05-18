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
  <div class="space-y-6">

    <!-- HEADER -->
    <div
      class="rounded-3xl bg-[#173B74] p-8 text-white shadow-lg"
    >
      <h1 class="text-3xl font-bold">
        🎉 Assessment Selesai
      </h1>

      <p class="mt-2 text-slate-300">
        Berikut hasil pengerjaan
        {{ assessment.title }}
      </p>

      <!-- STATS -->
      <div
        class="mt-8 grid gap-5 md:grid-cols-3"
      >
        <!-- SCORE -->
        <div
          class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm"
        >
          <p class="text-sm text-slate-300">
            Score
          </p>

          <h2 class="mt-2 text-5xl font-bold">
            {{ result.score }}
          </h2>
        </div>

        <!-- CORRECT -->
        <div
          class="rounded-2xl bg-emerald-500/20 p-5"
        >
          <p class="text-sm text-emerald-100">
            Jawaban Benar
          </p>

          <h2
            class="mt-2 text-5xl font-bold text-emerald-300"
          >
            {{ result.correct_answers }}
          </h2>
        </div>

        <!-- WRONG -->
        <div
          class="rounded-2xl bg-red-500/20 p-5"
        >
          <p class="text-sm text-red-100">
            Jawaban Salah
          </p>

          <h2
            class="mt-2 text-5xl font-bold text-red-300"
          >
            {{ result.wrong_answers }}
          </h2>
        </div>
      </div>
    </div>

    <!-- REVIEW -->
    <div class="space-y-5">

      <div
        v-for="(
          answer,
          index
        ) in result.answers"
        :key="answer.id"
        class="overflow-hidden rounded-3xl border bg-white shadow-sm"
        :class="
          answer.is_correct
            ? 'border-emerald-300'
            : 'border-red-300'
        "
      >

        <!-- TOP -->
        <div
          class="flex items-center justify-between border-b border-b-slate-200 bg-slate-50 px-6 py-4"
        >
          <h2
            class="font-bold text-slate-800"
          >
            Soal {{ index + 1 }}
          </h2>

          <div
            class="rounded-full px-3 py-1 text-xs font-bold"
            :class="
              answer.is_correct
                ? 'bg-emerald-100 text-emerald-700'
                : 'bg-red-100 text-red-700'
            "
          >
            {{
              answer.is_correct
                ? 'Benar'
                : 'Salah'
            }}
          </div>
        </div>

        <!-- CONTENT -->
        <div class="p-6">

          <!-- QUESTION -->
          <h3
            class="text-lg font-semibold text-slate-800"
          >
            {{
              answer.question.question
            }}
          </h3>

          <!-- ANSWERS -->
          <div
            class="mt-6 grid gap-4 md:grid-cols-2"
          >

            <!-- USER ANSWER -->
            <div
              :class="
                answer.is_correct
                  ? 'border-emerald-200 bg-emerald-50'
                  : 'border-red-200 bg-red-50'
              "
              class="rounded-2xl border p-4"
            >
              <p
                class="text-xs font-bold uppercase tracking-wide text-slate-500"
              >
                Jawaban Siswa
              </p>

              <h4
                class="mt-2 font-semibold"
                :class="
                  answer.is_correct
                    ? 'text-emerald-700'
                    : 'text-red-700'
                "
              >
                {{ answer.answer }}.
                {{
                  getOptionText(
                    answer.question,
                    answer.answer
                  )
                }}
              </h4>
            </div>

            <!-- CORRECT ANSWER -->
            <div
              class="rounded-2xl border border-blue-200 bg-blue-50 p-4"
            >
              <p
                class="text-xs font-bold uppercase tracking-wide text-slate-500"
              >
                Jawaban Benar
              </p>

              <h4
                class="mt-2 font-semibold text-blue-700"
              >
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
          <div
            class="mt-5 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600"
          >
            💡
            {{
              answer.question.explanation ??
              'Pelajari kembali materi terkait soal ini.'
            }}
          </div>

        </div>
      </div>
    </div>
  </div>
</template>