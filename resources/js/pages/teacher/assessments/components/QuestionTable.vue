```vue
<!-- resources/js/pages/teacher/assessments/components/QuestionTable.vue -->

<script setup lang="ts">
import {
  Pencil,
  Trash2,
  CheckCircle2,
} from 'lucide-vue-next';

import Swal from 'sweetalert2';

import { toast } from 'vue-sonner';

import { router } from '@inertiajs/vue3';

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

const emit = defineEmits([
  'edit',
]);

const removeQuestion = async (
  id: number
) => {

  const result =
    await Swal.fire({

      title:
        'Hapus soal?',

      text:
        'Soal akan dihapus permanen',

      icon:
        'warning',

      showCancelButton: true,

      confirmButtonText:
        'Ya, hapus',

      cancelButtonText:
        'Batal',

      confirmButtonColor:
        '#ef4444',
    });

  if (!result.isConfirmed) {
    return;
  }

  router.delete(
    `/teacher/questions/${id}`,
    {
      preserveScroll: true,

      onSuccess: () => {

        toast.success(
          'Soal berhasil dihapus'
        );
      },

      onError: () => {

        toast.error(
          'Gagal menghapus soal'
        );
      },
    },
  );
};
</script>

<template>
  <section class="rounded-xl bg-white p-4 shadow-sm">

    <!-- HEADER -->
    <div class="mb-4 flex items-center justify-between">

      <div>

        <h2 class="text-sm font-bold text-slate-800">

          Bank Soal
        </h2>

        <p class="mt-1 text-xs text-slate-500">

          {{ questions.length }}
          soal tersedia
        </p>
      </div>

      <div class="rounded-xl bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-600">

        {{ questions.length }}
        Soal
      </div>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto rounded-xl border border-slate-200">

      <table class="min-w-full">

        <!-- HEAD -->
        <thead class="bg-[#0B2B63] text-white">

          <tr>

            <th class="px-4 py-3 text-left text-[11px] font-semibold">

              No
            </th>

            <th class="px-4 py-3 text-left text-[11px] font-semibold">

              Soal
            </th>

            <th class="px-4 py-3 text-center text-[11px] font-semibold">

              Kunci
            </th>

            <th class="px-4 py-3 text-center text-[11px] font-semibold">

              Tipe
            </th>

            <th class="px-4 py-3 text-center text-[11px] font-semibold">

              Status
            </th>

            <th class="px-4 py-3 text-center text-[11px] font-semibold">

              Aksi
            </th>
          </tr>
        </thead>

        <!-- BODY -->
        <tbody>

          <tr v-for="(
question,
                index
            ) in questions" :key="question.id" class="border-b border-slate-100 transition hover:bg-slate-50">

            <!-- NO -->
            <td class="px-4 py-3 text-sm text-slate-700">

              {{ index + 1 }}
            </td>

            <!-- QUESTION -->
            <td class="max-w-md px-4 py-3 text-sm font-medium leading-relaxed whitespace-pre-line text-slate-800">

              {{ question.question }}
            </td>

            <!-- ANSWER -->
            <td class="px-4 py-3 text-center">

              <div
                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-[11px] font-bold text-blue-700">

                {{ question.answer }}
              </div>
            </td>

            <!-- TYPE -->
            <td class="px-4 py-3 text-center">

              <span class="rounded-full bg-purple-100 px-2.5 py-1 text-[10px] font-semibold text-purple-700">

                {{ question.type }}
              </span>
            </td>

            <!-- STATUS -->
            <td class="px-4 py-3 text-center">

              <div
                class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold text-emerald-700">

                <CheckCircle2 class="h-3 w-3" />

                {{ question.status }}
              </div>
            </td>

            <!-- ACTION -->
            <td class="px-4 py-3 text-center">

              <div class="flex items-center justify-center gap-2">

                <!-- EDIT -->
                <button @click="
                  emit(
                    'edit',
                    question
                  )
                  "
                  class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-[11px] font-semibold text-slate-700 transition hover:bg-slate-200">

                  <Pencil class="h-3.5 w-3.5" />

                  Edit
                </button>

                <!-- DELETE -->
                <button @click="
                  removeQuestion(
                    question.id
                  )
                  "
                  class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 px-3 py-2 text-[11px] font-semibold text-white transition hover:bg-red-600">

                  <Trash2 class="h-3.5 w-3.5" />

                  Hapus
                </button>
              </div>
            </td>
          </tr>

          <!-- EMPTY -->
          <tr v-if="!questions.length">

            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">

              Belum ada soal tersedia
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </section>
</template>
```
