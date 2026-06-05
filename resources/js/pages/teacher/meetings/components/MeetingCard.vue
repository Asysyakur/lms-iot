<!-- resources/js/pages/teacher/meetings/components/MeetingCard.vue -->

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
    class="cursor-pointer rounded-xl border bg-slate-50 p-4 text-left transition hover:bg-emerald-50" :class="active
        ? 'border-emerald-500 ring-2 ring-emerald-100'
        : 'border-slate-200'
      ">

    <div class="flex items-start justify-between gap-3">

      <!-- LEFT -->
      <div class="min-w-0 flex-1">

        <!-- NUMBER -->
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-xs font-bold text-white">

          {{ meeting.meeting_number }}
        </div>

        <!-- TITLE -->
        <h2 class="mt-3 line-clamp-2 text-sm font-bold leading-snug text-slate-800">

          {{ meeting.title }}
        </h2>

        <!-- SUBTITLE -->
        <p class="mt-1 text-[11px] text-slate-500">

          Pertemuan
          {{ meeting.meeting_number }}
        </p>

        <!-- TOGGLE -->
        <button @click.stop="toggleMeeting(meeting)"
          class="mt-3 rounded-lg px-3 py-1.5 text-[11px] font-semibold transition" :class="meeting.is_active
              ? 'bg-emerald-500 text-white hover:bg-emerald-600'
              : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
            ">

          {{
            meeting.is_active
              ? 'Aktif'
              : 'Draft'
          }}
        </button>
      </div>

      <!-- RIGHT -->
      <div class="flex flex-col items-end gap-2">

        <!-- STATUS -->
        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold text-emerald-700">

          {{ meeting.status }}
        </span>

        <!-- ACTION -->
        <div class="flex items-center gap-2">

          <!-- EDIT -->
          <button @click.stop="emit('edit', meeting)"
            class="rounded-lg bg-slate-100 p-2 text-slate-700 transition hover:bg-slate-200">

            <Pencil class="h-3.5 w-3.5" />
          </button>

          <!-- DELETE -->
          <button @click.stop="emit('delete', meeting)"
            class="rounded-lg bg-red-500 p-2 text-white transition hover:bg-red-600">

            <Trash2 class="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>