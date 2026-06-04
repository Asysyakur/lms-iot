```vue
<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import { Link } from '@inertiajs/vue3';

import {
  Users,
  ClipboardCheck,
  BookOpen,
  FileText,
  UserCog,
  Settings2,
  MonitorCheck,
  BarChart3,
} from 'lucide-vue-next';

defineOptions({
  layout: TeacherSidebarLayout,
});

const props = defineProps<{
  teacher: any;
  stats: any[];
  features: any[];
}>();

const iconMap: any = {
  Users,
  ClipboardCheck,
  BookOpen,
  FileText,
  UserCog,
  Settings2,
  MonitorCheck,
  BarChart3,
};
</script>

<template>
  <div class="space-y-5">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-2xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 p-5 text-white shadow-lg">

      <div
        class="absolute right-0 top-0 h-44 w-44 rounded-full bg-white/10" />

      <div class="relative z-10">

        <div class="mb-3">
          <span
            class="rounded-full bg-white/20 px-3 py-1 text-[10px] font-semibold uppercase tracking-wide">
            Dashboard Guru
          </span>
        </div>

        <h1 class="text-2xl font-black">
          Halo, {{ props.teacher.name || 'Guru' }}
        </h1>
      </div>
    </section>

    <!-- STATS -->
    <section
      class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

      <div
        v-for="stat in stats"
        :key="stat.title"
        class="rounded-2xl border bg-linear-to-br p-4 shadow-sm"
        :class="stat.color">

        <div class="flex items-start justify-between">

          <div>
            <p class="text-xs font-semibold text-slate-500">
              {{ stat.title }}
            </p>

            <h2 class="mt-1 text-3xl font-black text-slate-800">
              {{ stat.value }}
            </h2>

            <p class="mt-1 text-xs text-slate-500">
              {{ stat.desc }}
            </p>
          </div>

          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm">

            <component
              :is="iconMap[stat.icon]"
              class="h-5 w-5"
              :class="stat.iconColor" />
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section
      class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

      <div
        v-for="feature in features"
        :key="feature.title"
        class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

        <div
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">

          <component
            :is="iconMap[feature.icon]"
            class="h-5 w-5"
            :class="feature.color" />
        </div>

        <h2 class="mt-3 text-base font-bold text-slate-800">
          {{ feature.title }}
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-slate-500">
          {{ feature.desc }}
        </p>

        <Link
          :href="feature.href"
          class="mt-4 inline-block rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">

          Buka Menu →
        </Link>
      </div>
    </section>
  </div>
</template>
```
