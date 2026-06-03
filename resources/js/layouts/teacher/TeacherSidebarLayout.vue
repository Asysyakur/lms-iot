```vue
<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import {
  LayoutDashboard,
  Users,
  ClipboardCheck,
  BookOpen,
  FileText,
  BarChart3,
  LogOut,
  Menu,
} from 'lucide-vue-next';

import { Toaster } from 'vue-sonner';

const page = usePage();

const sidebarOpen = ref(false);

const menus = [
  {
    title: 'Dashboard',
    href: '/teacher/dashboard',
    icon: LayoutDashboard,
    color: 'bg-emerald-500/20 text-emerald-300',
  },

  {
    title: 'Siswa',
    href: '/teacher/students',
    icon: Users,
    color: 'bg-violet-500/20 text-violet-300',
  },

  {
    title: 'Pre-test',
    href: '/teacher/assessments/pretest',
    icon: ClipboardCheck,
    color: 'bg-amber-500/20 text-amber-300',
  },

  {
    title: 'Pertemuan',
    href: '/teacher/meetings',
    icon: BookOpen,
    color: 'bg-blue-500/20 text-blue-300',
  },

  {
    title: 'Post-test',
    href: '/teacher/assessments/posttest',
    icon: FileText,
    color: 'bg-rose-500/20 text-rose-300',
  },

  {
    title: 'Monitoring',
    href: '/teacher/reports',
    icon: BarChart3,
    color: 'bg-cyan-500/20 text-cyan-300',
  },
];
</script>

<template>
  <Toaster richColors position="top-right" />

  <div class="min-h-screen bg-slate-100">

    <!-- MOBILE HEADER -->
    <header
      class="sticky top-0 z-50 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 shadow-sm lg:hidden">

      <div>
        <h1 class="text-sm font-black text-slate-800">
          LMS IoT
        </h1>

        <p class="text-[11px] text-slate-500">
          Guru Panel
        </p>
      </div>

      <button
        @click="sidebarOpen = !sidebarOpen"
        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white transition hover:bg-slate-50">

        <Menu
          class="h-5 w-5 text-slate-700 transition-all duration-300"
          :class="
            sidebarOpen
              ? 'rotate-90 scale-0 opacity-0'
              : 'rotate-0 scale-100 opacity-100'
          "
        />

        <div
          class="absolute text-slate-700 transition-all duration-300"
          :class="
            sidebarOpen
              ? 'rotate-0 scale-100 opacity-100'
              : 'rotate-90 scale-0 opacity-0'
          ">
          ✕
        </div>
      </button>
    </header>

    <!-- OVERLAY -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" />

    <!-- SIDEBAR -->
    <aside
      :class="
        sidebarOpen
          ? 'translate-x-0'
          : '-translate-x-full lg:translate-x-0'
      "
      class="fixed top-0 left-0 z-50 flex h-screen w-64 flex-col overflow-y-auto bg-linear-to-b from-[#071A3D] to-[#0B2B63] text-white transition-transform duration-300">

      <!-- LOGO -->
      <div class="border-b border-white/10 px-5 py-5">
        <h1 class="text-lg font-black text-white">
          LMS IoT
        </h1>

        <p class="mt-1 text-xs text-slate-300">
          Guru Panel
        </p>
      </div>

      <!-- NAV -->
      <div class="flex-1 px-3 py-5">

        <div
          class="mb-4 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400">
          Navigation
        </div>

        <nav class="space-y-2">
          <Link
            v-for="menu in menus"
            :key="menu.title"
            :href="menu.href"
            @click="sidebarOpen = false"
            class="group flex items-center gap-3 rounded-xl px-3 py-2.5 transition"
            :class="
              page.url.startsWith(menu.href)
                ? 'bg-linear-to-r from-emerald-500 to-teal-500 text-white shadow-lg'
                : 'text-slate-300 hover:bg-white/10 hover:text-white'
            ">

            <div
              class="flex h-9 w-9 items-center justify-center rounded-lg"
              :class="
                page.url.startsWith(menu.href)
                  ? 'bg-white/20'
                  : menu.color
              ">

              <component
                :is="menu.icon"
                class="h-4 w-4" />
            </div>

            <span class="text-sm font-semibold">
              {{ menu.title }}
            </span>
          </Link>
        </nav>
      </div>

      <!-- FOOTER -->
      <div class="border-t border-white/10 p-3">
        <Link
          href="/logout"
          method="post"
          as="button"
          class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-slate-300 transition hover:bg-red-500/20 hover:text-red-300">

          <div
            class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-500/20">

            <LogOut class="h-4 w-4" />
          </div>

          <span class="text-sm font-semibold">
            Log out
          </span>
        </Link>
      </div>
    </aside>

    <!-- CONTENT -->
    <main
      class="min-w-0 overflow-x-hidden p-4 md:p-5 lg:ml-64">

      <slot />
    </main>
  </div>
</template>
```
