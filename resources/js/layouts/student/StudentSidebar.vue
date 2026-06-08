```vue
<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Logo from '@/../../public/LOGO.png';
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
    class="top-0 left-0 flex h-screen w-60 shrink-0 flex-col overflow-y-auto bg-linear-to-b from-[#071A3D] to-[#0B2B63] text-white shadow-2xl lg:fixed">

    <!-- LOGO -->
    <div class="p-4">
      <Link href="/student/dashboard" class="flex items-center gap-3">
        <img :src="Logo"
          class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-emerald-500 text-sm font-bold shadow-lg" />


        <div>
          <h1 class="text-sm font-bold leading-tight">
            Bit-Learn
          </h1>

          <p class="mt-0.5 text-xs text-slate-300">
            Student Panel
          </p>
        </div>
      </Link>
    </div>

    <!-- MENU -->
    <div class="flex-1 px-3 pb-4">
      <nav class="space-y-1.5">

        <!-- DASHBOARD -->
        <Link href="/student/dashboard" class="flex items-center justify-between rounded-xl px-3 py-2.5 transition"
          :class="page.url === '/student/dashboard'
            ? 'bg-linear-to-r from-emerald-500 to-teal-500 text-white shadow-lg'
            : 'text-slate-200 hover:bg-white/10'">
          <div class="flex items-center gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-500/20">
              <LayoutDashboard class="h-3.5 w-3.5 text-purple-300" />
            </div>

            <span class="text-sm font-medium">
              Dashboard
            </span>
          </div>
        </Link>

        <!-- PRE TEST -->
        <Link href="/student/assessments/pretest" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 transition"
          :class="page.url.startsWith('/student/assessments/pretest')
            ? 'bg-linear-to-r from-emerald-500 to-teal-500 text-white shadow-lg'
            : 'text-slate-200 hover:bg-white/10'">
          <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/20">
            <ClipboardCheck class="h-3.5 w-3.5 text-amber-300" />
          </div>

          <span class="text-sm font-medium">
            Pre-test
          </span>
        </Link>

        <!-- LINE -->
        <div class="my-3 border-t border-white/10" />

        <!-- MEETINGS -->
        <div v-for="meeting in meetings" :key="meeting.id" class="space-y-1">
          <!-- HEADER -->
          <button type="button" @click="toggleMeeting(meeting.id)"
            class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left transition hover:bg-white/10"
            :class="page.url.startsWith(`/student/meetings/${meeting.id}`)
              ? 'bg-white/10'
              : ''">
            <div class="flex items-center gap-2.5">
              <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100/10">
                <BookOpen class="h-3.5 w-3.5 text-slate-200" />
              </div>

              <span class="text-sm font-medium">
                {{ meeting.title }}
              </span>
            </div>

            <ChevronDown v-if="openedMeeting === meeting.id" class="h-3.5 w-3.5 text-slate-300" />

            <ChevronRight v-else class="h-3.5 w-3.5 text-slate-300" />
          </button>

          <!-- SUB MENU -->
          <div v-if="openedMeeting === meeting.id" class="ml-3 border-l border-white/10 pl-3">
            <div class="space-y-1">
              <Link :href="`/student/meetings/${meeting.id}`"
                class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-xs text-cyan-300 transition hover:bg-white/10">
                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-cyan-500/20">
                  <BookOpen class="h-3 w-3" />
                </div>

                <span>
                  Overview
                </span>
              </Link>

              <template v-for="menu in meeting.menus" :key="menu.title">
                <!-- ENABLE -->
                <Link v-if="menu.unlocked" :href="menu.href"
                  class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-xs text-slate-200 transition hover:bg-white/10">
                  <div class="flex h-6 w-6 items-center justify-center rounded-md bg-white/10">
                    <component :is="iconMap[menu.icon]" class="h-3 w-3" />
                  </div>

                  <div class="flex w-full items-center justify-between">
                    <span>
                      {{ menu.title }}
                    </span>

                    <span v-if="menu.completed"
                      class="rounded-full bg-emerald-500/20 px-1.5 py-0.5 text-[9px] font-semibold text-emerald-300">
                      ✓
                    </span>
                  </div>
                </Link>

                <!-- LOCKED -->
                <div v-else
                  class="flex cursor-not-allowed items-center gap-2 rounded-lg px-2.5 py-2 text-xs text-slate-500">
                  <div class="flex h-6 w-6 items-center justify-center rounded-md bg-white/5">
                    <Lock class="h-3 w-3" />
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
        <div class="my-3 border-t border-white/10" />

        <!-- POST TEST -->
        <Link href="/student/assessments/posttest" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 transition"
          :class="page.url.startsWith('/student/assessments/posttest')
            ? 'bg-linear-to-r from-emerald-500 to-teal-500 text-white shadow-lg'
            : 'text-slate-200 hover:bg-white/10'">
          <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500/20">
            <FileText class="h-3.5 w-3.5 text-emerald-300" />
          </div>

          <span class="text-sm font-medium">
            Post-test
          </span>
        </Link>
      </nav>
    </div>

    <!-- FOOTER -->
    <div class="border-t border-white/10 p-3">
      <Link href="/logout" method="post" as="button"
        class="flex w-full cursor-pointer items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm text-slate-300 transition hover:bg-red-500/20 hover:text-red-300">
        <LogOut class="h-4 w-4" />

        <span>Keluar</span>
      </Link>
    </div>
  </aside>
</template>
```
