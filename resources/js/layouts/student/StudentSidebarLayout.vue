```vue
<script setup lang="ts">
import { ref } from 'vue';
import { Menu, X } from 'lucide-vue-next';

import StudentSidebar from './StudentSidebar.vue';
import { Toaster } from 'vue-sonner';

const sidebarOpen = ref(false);
</script>

<template>
    <Toaster richColors position="top-right" />
    <div class="min-h-screen bg-slate-100">
        <!-- MOBILE TOPBAR -->
        <div
            class="sticky top-0 z-40 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-4 lg:hidden">
            <div>
                <h1 class="text-lg font-bold text-slate-800">
                    LMS IoT
                </h1>

                <p class="text-xs text-slate-500">
                    Blended Learning
                </p>
            </div>

            <button class="rounded-xl border border-slate-200 p-2" @click="sidebarOpen = true">
                <Menu class="h-5 w-5" />
            </button>
        </div>

        <div class="flex">
            <!-- BACKDROP -->
            <Transition name="fade">
                <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/40 lg:hidden" @click="sidebarOpen = false" />
            </Transition>

            <!-- MOBILE SIDEBAR -->
            <Transition name="slide">
                <div v-if="sidebarOpen" class="fixed left-0 top-0 z-50 h-screen w-[280px] lg:hidden">
                    <div class="relative h-full">
                        <!-- CLOSE -->
                        <button class="absolute right-4 top-4 z-50 rounded-lg bg-white/10 p-2 text-white"
                            @click="sidebarOpen = false">
                            <X class="h-5 w-5" />
                        </button>

                        <StudentSidebar />
                    </div>
                </div>
            </Transition>

            <!-- DESKTOP SIDEBAR -->
            <div class="hidden lg:block">
                <StudentSidebar />
            </div>

            <!-- CONTENT -->
            <main class="min-w-0 flex-1 p-4 md:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.25s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}
</style>
```
