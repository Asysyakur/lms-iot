<script setup lang="ts">
import { ref } from 'vue';

import {
    Menu,
    X,
} from 'lucide-vue-next';

import StudentSidebar from './StudentSidebar.vue';

import {
    Toaster,
} from 'vue-sonner';

const sidebarOpen =
    ref(false);
</script>

<template>
    <Toaster richColors position="top-right" />

    <div class="min-h-screen bg-slate-100">

        <!-- MOBILE TOPBAR -->
        <header
            class="sticky top-0 z-40 flex items-center justify-between border-b border-slate-200 bg-white/90 px-3 py-3 backdrop-blur lg:hidden">

            <!-- LEFT -->
            <div class="flex items-center gap-2.5">

                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-teal-500 text-sm font-black text-white shadow-lg">
                    IoT
                </div>

                <div>
                    <h1 class="text-sm font-black leading-tight text-slate-800">
                        LMS IoT
                    </h1>

                    <p class="text-[11px] text-slate-500">
                        Student Panel
                    </p>
                </div>
            </div>

            <!-- BURGER -->
            <button
                class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white shadow-sm transition hover:bg-slate-50"
                @click="sidebarOpen = !sidebarOpen">

                <Transition mode="out-in" name="icon">

                    <Menu
                        v-if="!sidebarOpen"
                        key="menu"
                        class="h-4.5 w-4.5 text-slate-700" />

                    <X
                        v-else
                        key="close"
                        class="h-4.5 w-4.5 text-slate-700" />

                </Transition>
            </button>
        </header>

        <div class="flex">

            <!-- BACKDROP -->
            <Transition name="fade">
                <div
                    v-if="sidebarOpen"
                    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px] lg:hidden"
                    @click="sidebarOpen = false" />
            </Transition>

            <!-- MOBILE SIDEBAR -->
            <Transition name="slide">
                <div
                    v-if="sidebarOpen"
                    class="fixed left-0 top-0 z-50 h-screen lg:hidden">

                    <StudentSidebar />
                </div>
            </Transition>

            <!-- DESKTOP SIDEBAR -->
            <div class="hidden lg:block">
                <StudentSidebar />
            </div>

            <!-- CONTENT -->
            <main class="min-w-0 flex-1 p-3 md:p-5 lg:ml-60">
                <slot />
            </main>

        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.22s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition:
        transform 0.25s ease,
        opacity 0.25s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
    opacity: 0;
}

.icon-enter-active,
.icon-leave-active {
    transition:
        opacity 0.15s ease,
        transform 0.15s ease;
}

.icon-enter-from,
.icon-leave-to {
    opacity: 0;
    transform: scale(0.75) rotate(90deg);
}
</style>