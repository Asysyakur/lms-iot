<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

defineOptions({
  layout: StudentSidebarLayout,
});

const props = defineProps<{
  type: 'pretest' | 'posttest';
}>();

const title = props.type === 'pretest' ? 'Pre-test' : 'Post-test';
</script>

<template>
  <div class="rounded-3xl bg-white p-8 shadow-sm">
    <h1 class="text-3xl font-bold text-slate-800">
      {{ title }} selesai
    </h1>

    <p class="mt-3 text-slate-600">
      Halaman hasil untuk {{ title }} akan ditampilkan di sini.
    </p>
  </div>
</template>