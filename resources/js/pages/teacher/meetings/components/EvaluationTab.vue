<script setup lang="ts">
import { Save } from 'lucide-vue-next';
</script>

<template>
  <section class="rounded-3xl bg-white p-6 shadow-sm">

    <h2 class="text-xl font-bold text-slate-800">
      📋 Evaluasi
    </h2>

    <div class="mt-6 space-y-5">

      <textarea
        rows="5"
        placeholder="Masukkan pertanyaan evaluasi..."
        class="w-full rounded-2xl border border-slate-200 p-4"
      />

      <button
        class="inline-flex items-center gap-2 rounded-2xl bg-rose-500 px-6 py-3 font-semibold text-white"
      >
        <Save class="h-5 w-5" />

        Simpan Evaluasi
      </button>

    </div>

  </section>
</template>