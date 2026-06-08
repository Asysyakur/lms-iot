<script setup lang="ts">
import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';
import { computed, ref } from 'vue';

const showAllActivities = ref(false);

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

const displayedActivities = computed(() => {
    return props.activities.slice(0, 5);
});
</script>

<template>
    <!-- HEADER -->
    <section
        class="relative overflow-hidden rounded-2xl bg-[#173B74] px-5 py-4 text-white shadow-lg">

        <!-- BG EFFECT -->
        <div
            class="absolute right-0 top-0 h-28 w-28 rounded-full bg-cyan-400/10" />

        <div
            class="absolute bottom-0 right-14 h-16 w-16 rounded-full bg-emerald-400/10" />

        <div
            class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">

            <!-- LEFT -->
            <div class="flex items-start gap-3">

                <!-- AVATAR -->
                <!-- <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-xl font-bold">
                    {{ props.student.name?.charAt(0) }}
                </div> -->

                <!-- INFO -->
                <div class="min-w-0">

                    <!-- BADGE -->
                    <div
                        class="mb-2 flex flex-wrap items-center gap-1.5">

                        <span
                            class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold text-emerald-700">
                            Profil Siswa
                        </span>

                        <span
                            class="rounded-full bg-lime-100 px-2.5 py-1 text-[10px] font-semibold text-lime-700">
                            ● Aktif Belajar
                        </span>
                    </div>

                    <!-- TITLE -->
                    <h1
                        class="text-lg font-bold leading-tight md:text-[32px]">
                        {{ props.student.name }}
                    </h1>

                    <!-- DETAIL -->
                    <p
                        class="mt-1 text-xs text-slate-300 md:text-sm">
                        Kelas {{ props.student.class }}

                        <span class="hidden md:inline">
                            &nbsp; | &nbsp;
                        </span>

                        <br class="md:hidden">

                        Semester Genap 2024/2025
                    </p>

                    <!-- TAG -->
                    <!-- <div class="mt-3 flex flex-wrap gap-1.5">

                        <span
                            class="rounded-full bg-purple-100 px-2.5 py-1 text-[10px] font-semibold text-purple-700">
                            Rata-rata Kuis:
                            {{ props.stats[1]?.value ?? 0 }}
                        </span>

                        <span
                            class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold text-amber-700">
                            Progress {{ props.progress }}%
                        </span>
                    </div> -->
                </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full xl:w-auto xl:text-right">

                <h2
                    class="text-2xl font-bold text-cyan-300 md:text-4xl">
                    {{ props.progress }}%
                </h2>

                <p
                    class="mt-1 text-xs text-slate-300">
                    Progress Pembelajaran
                </p>

                <div
                    class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/20 xl:w-44">

                    <div
                        :style="{
                            width: props.progress + '%',
                        }"
                        class="h-full rounded-full bg-cyan-400" />
                </div>
            </div>
        </div>
    </section>

    <!-- GRID -->
    <div
        class="mt-4 grid grid-cols-1 gap-4 xl:grid-cols-12">

        <!-- IDENTITAS -->
        <div
            class="rounded-xl bg-white p-4 shadow-sm xl:col-span-4">

            <h2
                class="text-[15px] font-bold text-slate-800">
                Identitas Siswa
            </h2>

            <p
                class="mb-4 text-[11px] text-slate-500">
                Data utama peserta didik
            </p>

            <div class="space-y-3 text-sm">

                <div
                    class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Nama Lengkap
                    </span>

                    <span
                        class="text-right font-semibold text-slate-800">
                        {{ props.student.name }}
                    </span>
                </div>

                <div
                    class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Kelas
                    </span>

                    <span
                        class="text-right font-semibold text-slate-800">
                        {{ props.student.class }}
                    </span>
                </div>

                <div
                    class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Program Keahlian
                    </span>

                    <span
                        class="max-w-[180px] text-right font-semibold text-slate-800">
                        Teknik Jaringan Komputer dan Telekomunikasi
                    </span>
                </div>

                <div
                    class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Status
                    </span>

                    <span
                        class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold text-emerald-700">
                        Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- RINGKASAN -->
        <div
            class="rounded-xl bg-white p-4 shadow-sm xl:col-span-8">

            <h2
                class="text-[15px] font-bold text-slate-800">
                Ringkasan Pembelajaran
            </h2>

            <p
                class="mb-4 text-[11px] text-slate-500">
                Statistik berdasarkan aktivitas dan nilai terbaru
            </p>

            <div
                class="grid grid-cols-2 gap-3 xl:grid-cols-4">

                <div
                    v-for="(item, index) in props.stats"
                    :key="index"
                    :class="item.color"
                    class="rounded-xl p-3">

                    <div
                        class="mb-2 flex h-6 w-6 items-center justify-center rounded-md bg-white text-[10px] font-bold">
                        {{ index + 1 }}
                    </div>

                    <h3
                        class="text-xl font-bold md:text-2xl">
                        {{ item.value }}
                    </h3>

                    <p
                        class="mt-1 text-xs font-medium">
                        {{ item.title }}
                    </p>
                </div>
            </div>
        </div>

        <!-- STATISTIK -->
        <div
            class="rounded-xl bg-white p-4 shadow-sm xl:col-span-6">

            <h2
                class="text-[15px] font-bold text-slate-800">
                Statistik Nilai
            </h2>

            <p
                class="mb-4 text-[11px] text-slate-500">
                Rekap nilai berdasarkan komponen pembelajaran
            </p>

            <div class="space-y-4">

                <div
                    v-for="(item, index) in props.scores"
                    :key="index">

                    <div
                        class="mb-1 flex items-center justify-between text-xs">

                        <span
                            class="font-medium text-slate-600">
                            {{ item.label }}
                        </span>

                        <span
                            class="font-bold text-slate-800">
                            {{ item.value }}
                        </span>
                    </div>

                    <div
                        class="h-2 overflow-hidden rounded-full bg-slate-200">

                        <div
                            :class="item.color"
                            class="h-full rounded-full"
                            :style="{
                                width: item.value + '%',
                            }" />
                    </div>
                </div>
            </div>
        </div>

        <!-- AKTIVITAS -->
        <div
            class="rounded-xl bg-white p-4 shadow-sm xl:col-span-6">

            <h2
                class="text-[15px] font-bold text-slate-800">
                Progress Aktivitas
            </h2>

            <p
                class="mb-4 text-[11px] text-slate-500">
                Capaian aktivitas belajar siswa
            </p>

            <div class="space-y-3">

                <div
                    v-for="(activity, index) in displayedActivities"
                    :key="index"
                    class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-3 py-2.5">

                    <div
                        class="flex items-center gap-3">

                        <div
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm text-emerald-600">
                            ✓
                        </div>

                        <span
                            class="text-xs font-medium text-slate-700">
                            {{ activity.title }}
                        </span>
                    </div>

                    <span
                        class="shrink-0 rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold text-emerald-700">
                        {{ activity.status }}
                    </span>
                </div>
            </div>

            <button
                v-if="props.activities.length > 5"
                @click="showAllActivities = true"
                class="mt-4 w-full rounded-xl bg-blue-600 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

                Lihat Semua Aktivitas
            </button>
        </div>
    </div>

    <!-- MODAL -->
    <div
        v-if="showAllActivities"
        @click="showAllActivities = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

        <div
            @click.stop
            class="max-h-[85vh] w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

            <!-- HEADER -->
            <div
                class="flex items-center justify-between border-b border-slate-200 p-4">

                <div>

                    <h2
                        class="text-lg font-bold text-slate-800">
                        Semua Aktivitas
                    </h2>

                    <p
                        class="mt-1 text-xs text-slate-500">
                        Riwayat lengkap aktivitas pembelajaran siswa
                    </p>
                </div>

                <button
                    @click="showAllActivities = false"
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-sm font-bold text-slate-600 transition hover:bg-slate-200">

                    ✕
                </button>
            </div>

            <!-- CONTENT -->
            <div
                class="max-h-[65vh] space-y-3 overflow-y-auto p-4">

                <div
                    v-for="(activity, index) in props.activities"
                    :key="index"
                    class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-3 py-2.5">

                    <div
                        class="flex items-center gap-3">

                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            ✓
                        </div>

                        <div>

                            <p
                                class="text-sm font-semibold text-slate-800">
                                {{ activity.title }}
                            </p>

                            <p
                                class="mt-0.5 text-xs text-slate-500">
                                Aktivitas pembelajaran siswa
                            </p>
                        </div>
                    </div>

                    <span
                        class="shrink-0 rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold text-emerald-700">
                        {{ activity.status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>