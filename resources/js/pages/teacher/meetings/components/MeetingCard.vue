<script setup lang="ts">
defineProps<{
  meeting: any;
  active: boolean;
}>();
</script>

<template>
  <button type="button"
    :class="
      active
        ? 'border-emerald-500 ring-2 ring-emerald-100'
        : 'border-slate-200'
    "
    class="rounded-3xl border bg-slate-50 p-5 text-left transition"
  >
    <div
      class="flex items-start justify-between"
    >
      <div>
        <div
          class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white"
        >
          {{ meeting.id }}
        </div>

        <h2
          class="mt-4 font-bold text-slate-800"
        >
          {{ meeting.title }}
        </h2>

        <p
          class="mt-1 text-sm text-slate-500"
        >
          Pertemuan
          {{ meeting.id }}
        </p>
      </div>

      <span
        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
      >
        {{ meeting.status }}
      </span>
    </div>
  </button>
</template>