```vue
<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

import {
  ChevronDown,
  ChevronRight,
  LayoutDashboard,
  ClipboardCheck,
  BookOpen,
  FileText,
  FlaskConical,
  FileSpreadsheet,
  ClipboardList,
  Lock,
  LogOut,
} from 'lucide-vue-next';

const page = usePage();

const meetings =
  computed(() =>
    page.props.sidebarMeetings ?? []
  );

const iconMap = {
  BookOpen,
  ClipboardCheck,
  FlaskConical,
  FileSpreadsheet,
  ClipboardList,
};

const currentMeetingId = page.url.match(
  /\/student\/meetings\/(\d+)/
)?.[1];

const openedMeeting = ref<number | null>(
  currentMeetingId
    ? Number(currentMeetingId)
    : null,
);

const toggleMeeting = (id: number) => {
  openedMeeting.value =
    openedMeeting.value === id ? null : id;
};
</script>

<template>
  <aside
    class="sticky top-0 flex h-screen w-72 shrink-0 flex-col overflow-y-auto bg-linear-to-b from-[#071A3D] to-[#0B2B63] text-white">
    <!-- LOGO -->
    <div class="p-6">
      <Link href="/student/dashboard" class="flex items-start gap-4">
        <div
          class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-lg font-bold shadow-lg">
          IoT
        </div>

        <div>
          <h1 class="text-lg font-bold leading-tight">
            Learning Management System
          </h1>

          <p class="mt-1 text-sm text-slate-300">
            IoT Micro:bit — Kelas XI TKJ
          </p>
        </div>
      </Link>
    </div>

    <!-- MENU -->
    <div class="flex-1 px-4 pb-6">
      <nav class="space-y-2">
        <!-- DASHBOARD -->
        <Link href="/student/dashboard"
          class="flex items-center justify-between rounded-xl bg-white/10 px-4 py-3 transition hover:bg-white/15">
          <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-500/20">
              <LayoutDashboard class="h-4 w-4 text-purple-300" />
            </div>

            <span class="font-medium">
              Dashboard
            </span>
          </div>
        </Link>

        <!-- PRE TEST -->
        <Link href="/student/assessments/pretest"
          class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-200 transition hover:bg-white/10">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/20">
            <ClipboardCheck class="h-4 w-4 text-amber-300" />
          </div>

          <span class="font-medium">
            Pre-test
          </span>
        </Link>

        <!-- LINE -->
        <div class="my-4 border-t border-white/10" />

        <!-- MEETINGS -->
        <div v-for="meeting in meetings" :key="meeting.id" class="space-y-2">
          <!-- HEADER -->
          <Link :href="`/student/meetings/${meeting.id}`"
            class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-left transition hover:bg-white/10"
            :class="page.url.startsWith(`/student/meetings/${meeting.id}`)
              ? 'bg-white/10'
              : ''
              ">
            <div class="flex items-center gap-3">
              <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100/10">
                <BookOpen class="h-4 w-4 text-slate-200" />
              </div>

              <span class="font-medium">
                {{ meeting.title }}
              </span>
            </div>

            <ChevronDown v-if="openedMeeting === meeting.id" class="h-4 w-4 text-slate-300" />

            <ChevronRight v-else class="h-4 w-4 text-slate-300" />
          </Link>

          <!-- SUB MENU -->
          <div v-if="openedMeeting === meeting.id" class="ml-4 border-l border-white/10 pl-4">
            <div class="space-y-2">
              <template v-for="menu in meeting.menus" :key="menu.title">
                <!-- ENABLE -->
                <Link v-if="menu.unlocked" :href="menu.href"
                  class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-200 transition hover:bg-white/10">
                  <div class="flex h-7 w-7 items-center justify-center rounded-md bg-white/10">
                    <component :is="iconMap[menu.icon]" class="h-3.5 w-3.5" />
                  </div>

                  <div class="flex w-full items-center justify-between">
                    <span>
                      {{ menu.title }}
                    </span>

                    <span v-if="menu.completed"
                      class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-[10px] font-semibold text-emerald-300">
                      ✓
                    </span>
                  </div>
                </Link>

                <!-- LOCKED -->
                <div v-else
                  class="flex cursor-not-allowed items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-500">
                  <div class="flex h-7 w-7 items-center justify-center rounded-md bg-white/5">
                    <Lock class="h-3.5 w-3.5" />
                  </div>

                  <span>
                    {{ menu.title }}
                  </span>
                </div>
              </template>
            </div>
          </div>
        </div>

        <!-- LINE -->
        <div class="my-4 border-t border-white/10" />

        <!-- POST TEST -->
        <Link href="/student/assessments/posttest"
          class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-200 transition hover:bg-white/10">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/20">
            <FileText class="h-4 w-4 text-emerald-300" />
          </div>

          <span class="font-medium">
            Post-test
          </span>
        </Link>
      </nav>
    </div>

    <!-- FOOTER -->
    <div class="border-t border-white/10 p-4">
      <Link href="/logout" method="post" as="button"
        class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-slate-300 transition hover:bg-red-500/20 hover:text-red-300">
        <LogOut class="h-5 w-5" />

        <span>Keluar</span>
      </Link>
    </div>
  </aside>
</template>
```
