<script setup lang="ts">
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
    layout: TeacherSidebarLayout,
});

const props = defineProps<{
    meetings: any[];
    studentsCount: number;
}>();
</script>

<template>
    <div class="space-y-6">

        <section
            class="rounded-3xl bg-white p-6 shadow-sm"
        >
            <h1
                class="text-2xl font-bold text-slate-800"
            >
                📘 Ringkasan Pertemuan
            </h1>

            <p
                class="mt-2 text-slate-500"
            >
                Monitoring progress aktivitas siswa.
            </p>
        </section>

        <div
            class="grid gap-6 lg:grid-cols-2"
        >
            <Link
                v-for="meeting in props.meetings"
                :key="meeting.id"
                :href="`/teacher/reports/meetings/${meeting.id}`"
                class="rounded-3xl bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
            >
                <div
                    class="flex items-center justify-between"
                >
                    <div>
                        <h2
                            class="text-xl font-bold text-slate-800"
                        >
                            {{ meeting.title }}
                        </h2>

                        <p
                            class="mt-2 text-sm text-slate-500"
                        >
                            {{ meeting.status }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl bg-emerald-100 px-4 py-2 font-bold text-emerald-700"
                    >
                        {{ meeting.completion }}%
                    </div>
                </div>

                <div
                    class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100"
                >
                    <div
                        class="h-full rounded-full bg-emerald-500"
                        :style="{
                            width:
                                meeting.completion + '%'
                        }"
                    />
                </div>

                <div
                    class="mt-4 text-sm text-slate-500"
                >
                    Praktik:
                    {{ meeting.practice_submissions }}

                    • LKPD:
                    {{ meeting.lkpd_submissions }}

                    • Evaluasi:
                    {{ meeting.evaluation_submissions }}
                </div>
            </Link>
        </div>
    </div>
</template>