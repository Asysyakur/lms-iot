<!-- resources/js/pages/teacher/assessments/components/QuestionTable.vue -->

<script setup lang="ts">
import {
  Pencil,
  CheckCircle2,
} from 'lucide-vue-next';

defineProps<{
  type: 'pretest' | 'posttest';

  questions: {
    id: number;
    question: string;
    answer: string;
    type: string;
    status: string;
  }[];
}>();
</script>

<template>
  <section
    class="rounded-3xl bg-white p-6 shadow-sm"
  >
    <div
      class="mb-6 flex items-center justify-between"
    >
      <div>
        <h2
          class="text-xl font-bold text-slate-800"
        >
          Bank Soal
        </h2>

        <p
          class="mt-1 text-sm text-slate-500"
        >
          Semua soal yang sudah
          ditambahkan.
        </p>
      </div>

      <div
        class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600"
      >
        {{ questions.length }}
        Soal
      </div>
    </div>

    <div
      class="overflow-x-auto"
    >
      <table
        class="min-w-full overflow-hidden rounded-2xl"
      >
        <thead
          class="bg-[#0B2B63] text-white"
        >
          <tr>
            <th
              class="px-5 py-4 text-left text-sm font-semibold"
            >
              No
            </th>

            <th
              class="px-5 py-4 text-left text-sm font-semibold"
            >
              Soal
            </th>

            <th
              class="px-5 py-4 text-center text-sm font-semibold"
            >
              Kunci
            </th>

            <th
              class="px-5 py-4 text-center text-sm font-semibold"
            >
              Tipe
            </th>

            <th
              class="px-5 py-4 text-center text-sm font-semibold"
            >
              Status
            </th>

            <th
              class="px-5 py-4 text-center text-sm font-semibold"
            >
              Aksi
            </th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(
              question, index
            ) in questions"
            :key="question.id"
            class="border-b border-slate-100 transition hover:bg-slate-50"
          >
            <!-- NO -->
            <td
              class="px-5 py-4 text-sm text-slate-700"
            >
              {{ index + 1 }}
            </td>

            <!-- QUESTION -->
            <td
              class="px-5 py-4 font-medium text-slate-800"
            >
              {{ question.question }}
            </td>

            <!-- ANSWER -->
            <td
              class="px-5 py-4 text-center"
            >
              <div
                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700"
              >
                {{
                  question.answer
                }}
              </div>
            </td>

            <!-- TYPE -->
            <td
              class="px-5 py-4 text-center"
            >
              <span
                class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"
              >
                {{
                  question.type
                }}
              </span>
            </td>

            <!-- STATUS -->
            <td
              class="px-5 py-4 text-center"
            >
              <div
                class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
              >
                <CheckCircle2
                  class="h-3 w-3"
                />

                {{
                  question.status
                }}
              </div>
            </td>

            <!-- ACTION -->
            <td
              class="px-5 py-4 text-center"
            >
              <button
                class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200"
              >
                <Pencil
                  class="h-4 w-4"
                />

                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>