<!-- resources/js/pages/teacher/meetings/components/LkpdTab.vue -->

<script setup lang="ts">
import {
  Upload,
  Save,
} from 'lucide-vue-next';
</script>

<template>
  <section
    class="rounded-3xl bg-white p-6 shadow-sm"
  >
    <h2
      class="text-xl font-bold text-slate-800"
    >
      📄 Pengaturan LKPD
    </h2>

    <div class="mt-6 space-y-5">

      <input
        type="file"
        class="w-full rounded-2xl border border-slate-200 p-3"
      />

      <input
        type="text"
        placeholder="Link Google Docs Template"
        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
      />

      <textarea
        rows="5"
        placeholder="Catatan pengumpulan LKPD..."
        class="w-full rounded-2xl border border-slate-200 p-4"
      />

      <button
        class="inline-flex items-center gap-2 rounded-2xl bg-orange-500 px-6 py-3 font-semibold text-white transition hover:bg-orange-600"
      >
        <Upload class="h-5 w-5" />

        Simpan LKPD
      </button>
    </div>
  </section>
</template>