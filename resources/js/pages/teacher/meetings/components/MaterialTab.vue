<script setup lang="ts">
import { Save } from 'lucide-vue-next';
</script>

<template>
  <section class="rounded-3xl bg-white p-6 shadow-sm">

    <h2 class="text-xl font-bold text-slate-800">
      📘 Pengaturan Materi
    </h2>

    <div class="mt-6 space-y-5">

      <!-- TITLE -->
      <input
        type="text"
        placeholder="Judul Materi"
        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
      />

      <!-- DESCRIPTION -->
      <textarea
        rows="4"
        placeholder="Deskripsi materi..."
        class="w-full rounded-2xl border border-slate-200 p-4"
      />

      <!-- FILE + VIDEO -->
      <div class="grid gap-5 md:grid-cols-2">

        <input
          type="file"
          class="rounded-2xl border border-slate-200 p-3"
        />

        <input
          type="text"
          placeholder="Link Video Youtube"
          class="rounded-2xl border border-slate-200 px-4 py-3"
        />

      </div>

      <!-- PEMANTIK -->
      <textarea
        rows="4"
        placeholder="Pertanyaan pemantik..."
        class="w-full rounded-2xl border border-slate-200 p-4"
      />

      <!-- REFLEKSI -->
      <textarea
        rows="4"
        placeholder="Pertanyaan refleksi..."
        class="w-full rounded-2xl border border-slate-200 p-4"
      />

      <!-- BUTTON -->
      <button
        class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
      >
        <Save class="h-5 w-5" />

        Simpan Materi
      </button>

    </div>

  </section>
</template>