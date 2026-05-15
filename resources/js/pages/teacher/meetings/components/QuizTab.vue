<script setup lang="ts">
import { Plus } from 'lucide-vue-next';
</script>

<template>
  <section class="rounded-3xl bg-white p-6 shadow-sm">

    <h2 class="text-xl font-bold text-slate-800">
      📝 Pengaturan Kuis
    </h2>

    <div class="mt-6 space-y-5">

      <!-- QUESTION -->
      <textarea
        rows="4"
        placeholder="Masukkan pertanyaan kuis..."
        class="w-full rounded-2xl border border-slate-200 p-4"
      />

      <!-- OPTIONS -->
      <div class="grid gap-5 md:grid-cols-2">

        <input
          type="text"
          placeholder="Opsi A"
          class="rounded-2xl border border-slate-200 px-4 py-3"
        />

        <input
          type="text"
          placeholder="Opsi B"
          class="rounded-2xl border border-slate-200 px-4 py-3"
        />

        <input
          type="text"
          placeholder="Opsi C"
          class="rounded-2xl border border-slate-200 px-4 py-3"
        />

        <input
          type="text"
          placeholder="Opsi D"
          class="rounded-2xl border border-slate-200 px-4 py-3"
        />

      </div>

      <!-- ANSWER -->
      <select
        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
      >
        <option>
          Kunci Jawaban A
        </option>

        <option>
          Kunci Jawaban B
        </option>

        <option>
          Kunci Jawaban C
        </option>

        <option>
          Kunci Jawaban D
        </option>
      </select>

      <!-- BUTTON -->
      <button
        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white hover:bg-emerald-600"
      >
        <Plus class="h-5 w-5" />

        Tambahkan Soal
      </button>

    </div>

  </section>
</template>