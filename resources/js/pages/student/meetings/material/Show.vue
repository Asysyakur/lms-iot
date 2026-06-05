<!-- resources/js/pages/student/meetings/materials/Show.vue -->

<script setup lang="ts">
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
    nextTick,
    watch,
} from 'vue';

import { Link } from '@inertiajs/vue3';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
    BookOpen,
    Download,
} from 'lucide-vue-next';

import axios from 'axios';

import { toast } from 'vue-sonner';

import * as pdfjsLib from 'pdfjs-dist';

pdfjsLib.GlobalWorkerOptions.workerSrc =
    new URL(
        'pdfjs-dist/build/pdf.worker.mjs',
        import.meta.url,
    ).toString();

defineOptions({
    layout: StudentSidebarLayout,
});

const props = defineProps<{
    meeting: any;
    material: any;
    progress: any;
}>();

let heartbeatInterval: any = null;

/**
 * PDF
 */
const pdfContainer =
    ref<HTMLElement | null>(null);

const readingProgress =
    ref<number>(0);

let saveTimeout: any = null;

const progressStorageKey =
    `material-progress-${props.meeting.id}`;

/**
 * REFLECTION
 */
const reflectionAnswers =
    ref<string[]>([]);

const reflectionSaved =
    ref(
        props.progress
            ?.reflection_completed
        ?? false
    );

const reflectionCompleted =
    computed(() => {

        return (
            reflectionAnswers.value.length ===
            props.material
                .reflection_questions
                .length
            &&
            reflectionAnswers.value.every(
                (answer) =>
                    answer?.trim() !== ''
            )
        );

    });

const triggerAnswer =
    ref(
        props.progress?.trigger_answer
        ?? ''
    );

const reflectionUnlocked =
    computed(() => {

        return (
            triggerAnswer.value
                .trim() !== ''
        );

    });

const canProceed =
    computed(() => {

        return (
            triggerAnswer.value.trim() !== ''
            &&
            readingProgress.value >= 70
            &&
            reflectionCompleted.value
            &&
            reflectionSaved.value
        );

    });

readingProgress.value =
    props.progress?.reading_progress
    ?? 0;

reflectionAnswers.value =
    props.progress?.reflection_answers
    ?? [];

/**
 * YOUTUBE
 */
const youtubeEmbedUrl =
    computed(() => {

        if (!props.material?.video_url) {
            return null;
        }

        const url =
            props.material.video_url;

        let videoId = '';

        if (url.includes('watch?v=')) {

            videoId =
                url.split('watch?v=')[1]
                    ?.split('&')[0];

        }

        else if (
            url.includes('youtu.be/')
        ) {

            videoId =
                url.split('youtu.be/')[1]
                    ?.split('?')[0];

        }

        else if (
            url.includes('/shorts/')
        ) {

            videoId =
                url.split('/shorts/')[1]
                    ?.split('?')[0];

        }

        else if (
            url.includes('/embed/')
        ) {

            videoId =
                url.split('/embed/')[1]
                    ?.split('?')[0];

        }

        if (!videoId) {
            return url;
        }

        return `https://www.youtube.com/embed/${videoId}`;

    });

/**
 * LOCAL SAVE
 */
const saveReadingProgressLocal =
    () => {

        localStorage.setItem(
            progressStorageKey,
            String(
                readingProgress.value
            )
        );

    };

/**
 * PROGRESS
 */
const updateProgress = () => {

    if (!pdfContainer.value) {
        return;
    }

    const el =
        pdfContainer.value;

    const maxScroll =
        el.scrollHeight -
        el.clientHeight;

    if (maxScroll <= 0) {

        readingProgress.value = 0;

        return;
    }

    const progress =
        (el.scrollTop / maxScroll)
        * 100;

    readingProgress.value =
        Math.max(
            readingProgress.value,
            Math.min(
                100,
                Math.max(
                    0,
                    Math.ceil(progress)
                )
            )
        );

    clearTimeout(saveTimeout);

    saveTimeout = setTimeout(async () => {

        saveReadingProgressLocal();

        await saveReadingProgress();

    }, 1000);
};

/**
 * PDF
 */
const renderPdf = async () => {

    if (
        !props.material?.pdf_url ||
        !pdfContainer.value
    ) {
        return;
    }

    pdfContainer.value.innerHTML =
        '';

    const pdf =
        await pdfjsLib
            .getDocument(
                props.material.pdf_url
            )
            .promise;

    for (
        let pageNum = 1;
        pageNum <= pdf.numPages;
        pageNum++
    ) {

        const page =
            await pdf.getPage(
                pageNum
            );

        const viewport =
            page.getViewport({
                scale: 1,
            });

        const pageWrapper =
            document.createElement(
                'div'
            );

        pageWrapper.className =
            'relative mb-4 flex items-center justify-center rounded-xl bg-white shadow-sm';

        const canvas =
            document.createElement(
                'canvas'
            );

        const context =
            canvas.getContext('2d');

        if (!context) {
            continue;
        }

        canvas.width =
            viewport.width;

        canvas.height =
            viewport.height;

        canvas.style.width =
            `${viewport.width}px`;

        canvas.style.height =
            `${viewport.height}px`;

        pageWrapper.appendChild(
            canvas
        );

        pdfContainer.value.appendChild(
            pageWrapper
        );

        await page.render({
            canvasContext:
                context as any,
            viewport,
        } as any).promise;
    }

    nextTick(() => {

        updateProgress();

        pdfContainer.value?.addEventListener(
            'scroll',
            () => {

                requestAnimationFrame(
                    updateProgress
                );

            },
            {
                passive: true,
            }
        );

    });

};

onMounted(async () => {

    readingProgress.value =
        props.progress?.reading_progress
        ?? 0;

    renderPdf();

    await axios.post(
        `/student/meetings/${props.meeting.id}/start-reading`
    );

    heartbeatInterval =
        setInterval(async () => {

            await axios.post(
                `/student/meetings/${props.meeting.id}/heartbeat`
            );

        }, 30000);

});

onBeforeUnmount(() => {

    if (heartbeatInterval) {

        clearInterval(
            heartbeatInterval
        );

    }

});

const saveReadingProgress =
    async () => {

        try {

            await axios.post(
                `/student/meetings/${props.meeting.id}/material/progress`,
                {
                    reading_progress:
                        readingProgress.value,
                }
            );

        } catch (error) {

            console.error(error);

        }
    };

const saveTriggerAnswer =
    async () => {

        try {

            await axios.post(
                `/student/meetings/${props.meeting.id}/material/progress`,
                {
                    trigger_answer:
                        triggerAnswer.value,
                }
            );

            toast.success(
                'Jawaban berhasil disimpan'
            );

        } catch (error) {

            toast.error(
                'Gagal menyimpan jawaban'
            );
        }
    };

const saveReflection =
    async () => {

        try {

            await axios.post(
                `/student/meetings/${props.meeting.id}/material/progress`,
                {
                    reflection_answers:
                        reflectionAnswers.value,

                    reflection_completed:
                        reflectionCompleted.value,
                }
            );

            reflectionSaved.value = true;

            toast.success(
                'Refleksi berhasil disimpan'
            );

        } catch (error) {

            toast.error(
                'Gagal menyimpan refleksi'
            );
        }
    };

watch(
    reflectionAnswers,
    () => {

        reflectionSaved.value = false;

    },
    {
        deep: true,
    }
);
</script>

<template>
    <div class="space-y-4">

        <!-- HEADER -->
        <section class="relative overflow-hidden rounded-xl bg-[#173B74] px-4 py-3 text-white shadow-sm">

            <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-cyan-400/10" />

            <div>

                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-semibold text-blue-700">

                    {{ meeting.title }}
                </span>

                <h1 class="mt-2 text-xl font-bold md:text-2xl">

                    {{ material.title }}
                </h1>

                <p class="mt-1 max-w-2xl text-xs text-slate-300">

                    {{ material.description }}
                </p>
            </div>
        </section>

        <!-- VIDEO -->
        <section v-if="material.video_url" class="rounded-xl bg-white p-3 shadow-sm">

            <h2 class="mb-3 text-sm font-bold text-slate-800">

                Video Pembelajaran
            </h2>

            <iframe v-if="youtubeEmbedUrl" :src="youtubeEmbedUrl" class="h-[280px] w-full rounded-lg xl:h-[320px]"
                frameborder="0" allowfullscreen />
        </section>

        <!-- PEMANTIK -->
        <section class="rounded-xl bg-white p-3 shadow-sm">

            <div class="mb-4 flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-100">

                        <BookOpen class="h-4 w-4 text-purple-600" />
                    </div>

                    <div>

                        <h2 class="text-sm font-bold text-slate-800">

                            Pertanyaan Pemantik
                        </h2>

                        <p class="text-xs text-slate-500">

                            Jawab sebelum membaca materi
                        </p>
                    </div>
                </div>

                <span class="rounded-full bg-purple-100 px-2 py-1 text-[10px] font-semibold text-purple-700">

                    Wajib
                </span>
            </div>

            <div class="space-y-4">

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        {{ material.trigger_question }}
                    </label>

                    <textarea v-model="triggerAnswer" rows="3" placeholder="Tulis jawabanmu..."
                        class="w-full rounded-lg border border-slate-200 p-2.5 text-sm outline-none transition focus:border-blue-500" />
                </div>

                <div class="flex justify-end">

                    <button :disabled="triggerAnswer.trim() === ''" @click="saveTriggerAnswer" :class="triggerAnswer.trim() !== ''
                        ? 'bg-purple-600 hover:bg-purple-700'
                        : 'cursor-not-allowed bg-slate-200 text-slate-500'
                        " class="rounded-lg px-4 py-2 text-xs font-semibold text-white transition">

                        Simpan Jawaban
                    </button>
                </div>
            </div>
        </section>

        <!-- PROGRESS -->
        <section class="rounded-xl bg-white p-3 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-sm font-bold text-slate-800">

                        Progress Membaca
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">

                        Progress membaca materi
                    </p>
                </div>

                <h2 class="text-3xl font-bold text-emerald-500">

                    {{ readingProgress }}%
                </h2>
            </div>

            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">

                <div class="h-full rounded-full bg-emerald-500 transition-all duration-300" :style="{
                    width:
                        readingProgress +
                        '%',
                }" />
            </div>
        </section>

        <!-- PDF -->
        <section class="rounded-xl bg-white p-3 shadow-sm">

            <div ref="pdfContainer"
                class="flex h-[520px] flex-col items-center overflow-y-scroll rounded-xl border border-slate-200 bg-slate-100 p-4" />
        </section>

        <!-- REFLECTION -->
        <section class="rounded-xl bg-white p-3 shadow-sm">

            <div class="mb-4 flex items-center justify-between">

                <div>

                    <h2 class="text-sm font-bold text-slate-800">

                        Refleksi
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">

                        Isi refleksi sebelum lanjut
                    </p>
                </div>

                <span class="rounded-full bg-purple-100 px-2 py-1 text-[10px] font-semibold text-purple-700">

                    Wajib
                </span>
            </div>

            <div v-if="reflectionUnlocked" class="space-y-4">

                <div v-for="(question, index) in material.reflection_questions" :key="index">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        {{ index + 1 }}.
                        {{ question }}
                    </label>

                    <textarea v-model="reflectionAnswers[index]" rows="3"
                        class="w-full rounded-lg border border-slate-200 p-2.5 text-sm outline-none transition focus:border-blue-500" />
                </div>

                <div class="flex justify-end">

                    <button :disabled="!reflectionCompleted || readingProgress < 70" @click="saveReflection" :class="reflectionCompleted && readingProgress >= 70
                        ? 'bg-purple-600 hover:bg-purple-700'
                        : 'cursor-not-allowed bg-slate-200 text-slate-500'
                        " class="rounded-lg px-4 py-2 text-xs font-semibold text-white transition">

                        {{
                            reflectionSaved
                                ? 'Tersimpan'
                                : 'Simpan Refleksi'
                        }}
                    </button>
                </div>
            </div>

            <!-- LOCKED -->
            <div v-else class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">

                Isi pertanyaan pemantik
                terlebih dahulu untuk membuka
                refleksi.
            </div>

            <!-- INFO -->
            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-3 text-xs text-blue-700">

                Kuis akan terbuka setelah
                refleksi selesai diisi.
            </div>
        </section>

        <!-- DOWNLOAD -->
        <section class="rounded-xl bg-white p-3 shadow-sm">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100">

                        <Download class="h-5 w-5 text-red-500" />
                    </div>

                    <div>

                        <h2 class="text-sm font-bold text-slate-800">

                            Unduh Materi PDF
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">

                            Download materi untuk
                            belajar offline.
                        </p>
                    </div>
                </div>

                <a v-if="material.pdf_url" :href="material.pdf_url" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">

                    <Download class="h-4 w-4" />

                    Unduh PDF
                </a>
            </div>
        </section>

        <!-- FOOTER -->
        <section class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            <a :href="`/student/meetings/${props.meeting.id}`"
                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

                ← Kembali
            </a>

            <Link v-if="canProceed" :href="`/student/meetings/${props.meeting.id}/quiz`"
                class="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">

                Lanjut ke Kuis →
            </Link>

            <button v-else disabled
                class="cursor-not-allowed rounded-lg bg-slate-300 px-4 py-2 text-sm font-semibold text-slate-500">

                Selesaikan Refleksi
            </button>
        </section>
    </div>
</template>

<style>
.textLayer {
    position: absolute;
    inset: 0;
    overflow: hidden;
}

.textLayer span {
    position: absolute;
    color: transparent;
    white-space: pre;
    cursor: text;
    transform-origin: left bottom;
}
</style>