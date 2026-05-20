```vue
<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { ref, computed } from 'vue';

const showAllActivities =
    ref(false);

const displayedActivities =
    computed(() => {
        return props.activities.slice(0, 5);
    });

defineOptions({
    layout: StudentSidebarLayout,
});

const props = defineProps<{
    student: any;
    stats: any[];
    scores: any[];
    activities: any[];
    progress: number;
}>();

</script>

```vue
<template>
    <!-- HEADER -->
    <section class="relative overflow-hidden rounded-3xl bg-[#173B74] p-4 text-white shadow-lg md:p-6">
        <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-cyan-400/10" />

        <div class="absolute bottom-0 right-20 h-24 w-24 rounded-full bg-emerald-400/10" />

        <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
            <!-- LEFT -->
            <div class="flex items-start gap-4 md:gap-5">
                <!-- AVATAR -->
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-2xl font-bold md:h-16 md:w-16 md:text-3xl">
                    {{ props.student.name?.charAt(0) }}
                </div>

                <!-- INFO -->
                <div class="min-w-0">
                    <!-- BADGES -->
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold text-emerald-700">
                            Profil Siswa
                        </span>

                        <span class="rounded-full bg-lime-100 px-3 py-1 text-[11px] font-semibold text-lime-700">
                            ● Aktif Belajar
                        </span>
                    </div>

                    <!-- NAME -->
                    <h1 class="text-2xl font-bold leading-tight md:text-4xl">
                        {{ props.student.name }}
                    </h1>

                    <!-- DETAIL -->
                    <p class="mt-1 text-sm leading-relaxed text-slate-300 md:text-base">
                        Kelas {{ props.student.class }}

                        <span class="hidden md:inline">
                            &nbsp; | &nbsp;
                        </span>

                        <br class="md:hidden">

                        Semester Genap 2024/2025
                    </p>

                    <!-- TAG -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="rounded-full bg-purple-100 px-3 py-1 text-[11px] font-semibold text-purple-700">
                            Rata-rata Kuis: {{ props.stats[1]?.value ?? 0 }}
                        </span>

                        <span class="rounded-full bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-700">
                            Progress {{ props.progress }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full xl:w-auto xl:text-right">
                <h2 class="text-4xl font-bold text-cyan-300 md:text-5xl">
                    {{ props.progress }}%
                </h2>

                <p class="mt-2 text-sm text-slate-300">
                    Progress Pembelajaran
                </p>

                <div class="mt-3 h-3 w-full overflow-hidden rounded-full bg-white/20 xl:w-52">
                    <div :style="{
                        width: props.progress + '%'
                    }" class="h-full rounded-full bg-cyan-400" />
                </div>
            </div>
        </div>
    </section>

    <!-- GRID -->
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-12">
        <!-- IDENTITAS -->
        <div class="rounded-3xl bg-white p-5 shadow-sm md:p-6 xl:col-span-4">
            <h2 class="text-lg font-bold text-slate-800">
                Identitas Siswa
            </h2>

            <p class="mb-6 text-sm text-slate-500">
                Data utama peserta didik
            </p>

            <div class="space-y-5 text-sm">
                <div class="flex items-start justify-between gap-5">
                    <span class="text-slate-500">
                        Nama Lengkap
                    </span>

                    <span class="text-right font-semibold text-slate-800">
                        {{ props.student.name }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-5">
                    <span class="text-slate-500">
                        Kelas
                    </span>

                    <span class="text-right font-semibold text-slate-800">
                        {{ props.student.class }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-5">
                    <span class="text-slate-500">
                        Program Keahlian
                    </span>

                    <span class="max-w-[220px] text-right font-semibold text-slate-800">
                        Teknik Jaringan Komputer dan Telekomunikasi
                    </span>
                </div>

                <div class="flex items-start justify-between gap-5">
                    <span class="text-slate-500">
                        Status
                    </span>

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- RINGKASAN -->
        <div class="rounded-3xl bg-white p-5 shadow-sm md:p-6 xl:col-span-8">
            <h2 class="text-lg font-bold text-slate-800">
                Ringkasan Pembelajaran
            </h2>

            <p class="mb-6 text-sm text-slate-500">
                Statistik berdasarkan aktivitas dan nilai terbaru
            </p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div v-for="(item, index) in props.stats" :key="index" :class="item.color" class="rounded-2xl p-5">
                    <div class="mb-4 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm font-bold">
                        {{ index + 1 }}
                    </div>

                    <h3 class="text-3xl font-bold md:text-4xl">
                        {{ item.value }}
                    </h3>

                    <p class="mt-2 text-sm font-medium">
                        {{ item.title }}
                    </p>

                    <span class="mt-3 block text-sm font-semibold">
                        {{ item.status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- STATISTIK NILAI -->
        <div class="rounded-3xl bg-white p-5 shadow-sm md:p-6 xl:col-span-6">
            <h2 class="text-lg font-bold text-slate-800">
                Statistik Nilai
            </h2>

            <p class="mb-6 text-sm text-slate-500">
                Rekap nilai berdasarkan komponen pembelajaran
            </p>

            <div class="space-y-5">
                <div v-for="(item, index) in props.scores" :key="index">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-600">
                            {{ item.label }}
                        </span>

                        <span class="font-bold text-slate-800">
                            {{ item.value }}
                        </span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                        <div :class="item.color" class="h-full rounded-full" :style="{
                            width: item.value + '%',
                        }" />
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
                Nilai minimum kuis adalah 80. Jika belum mencapai
                batas, siswa dapat mengulang kuis.
            </div>
        </div>

        <!-- AKTIVITAS -->
        <div class="rounded-3xl bg-white p-5 shadow-sm md:p-6 xl:col-span-6">
            <h2 class="text-lg font-bold text-slate-800">
                Progress Aktivitas
            </h2>

            <p class="mb-6 text-sm text-slate-500">
                Capaian aktivitas belajar siswa
            </p>

            <div class="space-y-4">
                <div v-for="(activity, index) in displayedActivities" :key="index"
                    class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 p-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            ✓
                        </div>

                        <span class="text-sm font-medium text-slate-700 md:text-base">
                            {{ activity.title }}
                        </span>
                    </div>

                    <span class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        {{ activity.status }}
                    </span>
                </div>
            </div>

            <button @click="showAllActivities = true" v-if="props.activities.length > 5"
                class="mt-6 w-full rounded-2xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700">
                Lihat Semua Aktivitas
            </button>
        </div>
    </div>
    <!-- MODAL ALL ACTIVITIES -->
    <div v-if="showAllActivities" @click="showAllActivities = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.stop class="max-h-[85vh] w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl">
            <!-- HEADER -->
            <div class="flex items-center justify-between border-b border-slate-200 p-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">
                        Semua Aktivitas
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Riwayat lengkap aktivitas pembelajaran siswa
                    </p>
                </div>

                <button @click="showAllActivities = false"
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-xl font-bold text-slate-600 transition hover:bg-slate-200">
                    ✕
                </button>
            </div>

            <!-- CONTENT -->
            <div class="max-h-[65vh] space-y-4 overflow-y-auto p-6">
                <div v-for="(activity, index) in props.activities" :key="index"
                    class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 p-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            ✓
                        </div>

                        <div>
                            <p class="font-semibold text-slate-800">
                                {{ activity.title }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Aktivitas pembelajaran siswa
                            </p>
                        </div>
                    </div>

                    <span class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        {{ activity.status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
```

```
