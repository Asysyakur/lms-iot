<script setup lang="ts">
import axios from 'axios';
import {
  Pencil,
  Trash2,
} from 'lucide-vue-next';

defineProps<{
  meeting: any;
  active: boolean;
}>();

const emit = defineEmits([
  'click',
  'edit',
  'delete',
]);

const toggleMeeting =
  async (meeting: any) => {

    const response =
      await axios.patch(
        `/teacher/meetings/${meeting.id}/toggle`
      );

    meeting.is_active =
      response.data.meeting.is_active;
  };
</script>

<template>
  <div @click="$emit('click')"
    class="cursor-pointer max-w-full min-w-0 rounded-3xl border bg-slate-50 p-5 text-left transition hover:bg-emerald-50"
    :class="active
      ? 'border-emerald-500 ring-2 ring-emerald-100'
      : 'border-slate-200'
      ">
    <div class="flex min-w-0 items-start justify-between gap-3">
      <div class="flex min-w-0 flex-col gap-2">
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white">
          {{ meeting.meeting_number }}
        </div>

        <h2 class="mt-4 truncate font-bold text-slate-800">
          {{ meeting.title }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
          Pertemuan {{ meeting.meeting_number }}
        </p>

        <!-- TOGGLE -->
        <button @click.stop="toggleMeeting(meeting)"
          class="cursor-pointer rounded-xl px-3 py-2 text-xs font-bold transition" :class="meeting.is_active
            ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white'
            : 'bg-slate-200 text-slate-700 hover:bg-slate-300 hover:text-slate-900'
            ">
          {{
            meeting.is_active
              ? 'Aktif'
              : 'Draft'
          }}
        </button>
      </div>

      <div class="flex items-start gap-2">
        <!-- STATUS -->
        <span class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
          {{ meeting.status }}
        </span>

        <!-- EDIT -->
        <button @click.stop="emit('edit', meeting)"
          class="cursor-pointer rounded-xl bg-slate-100 p-2 text-slate-700 transition hover:bg-slate-200">
          <Pencil class="h-4 w-4" />
        </button>

        <!-- DELETE -->
        <button @click.stop="emit('delete', meeting)"
          class="cursor-pointer rounded-xl bg-red-500 p-2 text-white transition hover:bg-red-600">
          <Trash2 class="h-4 w-4" />
        </button>
      </div>
    </div>
  </div>
</template>