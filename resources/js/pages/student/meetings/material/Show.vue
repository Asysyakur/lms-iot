<!-- resources/js/pages/student/meetings/materials/Show.vue -->

<script setup lang="ts">
import {
    ref,
    computed,
    onMounted,
    nextTick,
    watch,
} from 'vue';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
    BookOpen,
    Download,
} from 'lucide-vue-next';
import axios from 'axios';
import Swal from 'sweetalert2';

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

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

const reflectionSaved =
    ref(
        props.progress
            ?.reflection_completed
        ?? false
    );

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

const triggerAnswer =
    ref(
        props.progress?.trigger_answer
        ?? ''
    );

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

        /**
         * WATCH
         */
        if (url.includes('watch?v=')) {

            videoId =
                url.split('watch?v=')[1]
                    ?.split('&')[0];

        }

        /**
         * SHORT URL
         */
        else if (
            url.includes('youtu.be/')
        ) {

            videoId =
                url.split('youtu.be/')[1]
                    ?.split('?')[0];

        }

        /**
         * SHORTS
         */
        else if (
            url.includes('/shorts/')
        ) {

            videoId =
                url.split('/shorts/')[1]
                    ?.split('?')[0];

        }

        /**
         * EMBED
         */
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
* SAVE READING PROGRESS LOCAL
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
 * LOAD READING PROGRESS LOCAL
 */
const loadReadingProgressLocal =
    () => {

        const saved =
            localStorage.getItem(
                progressStorageKey
            );

        if (!saved) {
            return;
        }

        readingProgress.value =
            Number(saved);

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

    saveTimeout = setTimeout(() => {

        saveReadingProgressLocal();

    }, 500);
};

/**
 * RENDER PDF
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
                scale: 2,
            });

        /**
         * PAGE WRAPPER
         */
        const pageWrapper =
            document.createElement(
                'div'
            );

        pageWrapper.className =
            'relative mb-6 flex items-center justify-center rounded-2xl bg-white shadow';

        /**
         * CANVAS
         */
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

        /**
         * RENDER PDF KE CANVAS
         */
        await page.render({
            canvasContext:
                context as any,

            viewport,
        } as any).promise;

        /**
 * TEXT LAYER MANUAL
 */
        const textLayer =
            document.createElement('div');

        textLayer.style.position =
            'absolute';

        textLayer.style.left =
            '0';

        textLayer.style.top =
            '0';

        textLayer.style.width =
            `${viewport.width}px`;

        textLayer.style.height =
            `${viewport.height}px`;

        textLayer.style.pointerEvents =
            'auto';

        textLayer.style.userSelect =
            'text';

        pageWrapper.appendChild(
            textLayer
        );

        const textContent =
            await page.getTextContent();

        textContent.items.forEach(
            (item: any) => {

                const span =
                    document.createElement(
                        'span'
                    );

                const tx =
                    pdfjsLib.Util.transform(
                        viewport.transform,
                        item.transform
                    );

                const angle =
                    Math.atan2(
                        tx[1],
                        tx[0]
                    );

                const fontHeight =
                    Math.sqrt(
                        tx[2] * tx[2] +
                        tx[3] * tx[3]
                    );

                span.textContent =
                    item.str;

                span.style.position =
                    'absolute';

                span.style.left =
                    `${tx[4]}px`;

                span.style.top =
                    `${tx[5] - fontHeight}px`;

                span.style.fontSize =
                    `${fontHeight}px`;

                span.style.fontFamily =
                    'sans-serif';

                span.style.transform =
                    `rotate(${angle}rad)`;

                span.style.transformOrigin =
                    'left bottom';

                span.style.whiteSpace =
                    'pre';

                span.style.color =
                    'transparent';

                span.style.cursor =
                    'text';

                span.style.lineHeight =
                    '1';

                textLayer.appendChild(
                    span
                );
            }
        );

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

onMounted(() => {

    loadReadingProgressLocal();

    renderPdf();

});

/**
 * SAVE TRIGGER ANSWER
 */
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

            Toast.fire({
                icon: 'success',
                title: 'Jawaban pemantik berhasil disimpan',
            });

        } catch (error) {

            console.error(error);

            Toast.fire({
                icon: 'error',
                title: 'Gagal menyimpan jawaban pemantik',
            });

        }

    };

/**
 * SAVE REFLECTION
 */
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

            Toast.fire({
                icon: 'success',
                title: 'Refleksi berhasil disimpan',
            });

        } catch (error) {

            console.error(error);

            Toast.fire({
                icon: 'error',
                title: 'Gagal menyimpan refleksi',
            });

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
    <div class="space-y-6">
        <!-- HEADER -->
        <section class="relative overflow-hidden rounded-3xl bg-[#173B74] p-6 text-white shadow-lg">
            <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-cyan-400/10" />

            <div>
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ meeting.title }}
                </span>

                <h1 class="mt-4 text-3xl font-bold">
                    {{ material.title }}
                </h1>

                <p class="mt-2 max-w-3xl text-slate-300">
                    {{ material.description }}
                </p>
            </div>
        </section>

        <section v-if="material.video_url" class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-slate-800">
                🎥 Video Pembelajaran
            </h2>

            <iframe v-if="youtubeEmbedUrl" :src="youtubeEmbedUrl" class="h-[400px] w-full rounded-2xl" frameborder="0"
                allowfullscreen />
        </section>

        <!-- PERTANYAAN PEMANTIK -->
        <section class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">
                        <BookOpen class="h-5 w-5 text-purple-600" />
                    </div>

                    <div>
                        <h2 class="font-bold text-slate-800">
                            Pertanyaan
                            Pemantik
                        </h2>

                        <p class="text-sm text-slate-500">
                            Jawablah
                            pertanyaan
                            berikut sebelum
                            membaca materi.
                        </p>
                    </div>
                </div>

                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                    Wajib diisi
                </span>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        {{ material.trigger_question }}
                    </label>

                    <textarea v-model="triggerAnswer" rows="5" placeholder="Tulis jawabanmu di sini..."
                        class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500" />
                </div>

                <div class="flex justify-end">
                    <button :disabled="triggerAnswer.trim() === ''" @click="saveTriggerAnswer" :class="triggerAnswer.trim() !== ''
                            ? 'bg-purple-600 hover:bg-purple-700'
                            : 'cursor-not-allowed bg-slate-200 text-slate-500'
                        " class="cursor-pointer rounded-2xl px-6 py-3 text-sm font-semibold text-white transition">
                        Simpan Jawaban
                    </button>
                </div>
            </div>
        </section>

        <!-- READING PROGRESS -->
        <section class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-800">
                        📖 Progress Membaca
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Kamu telah membaca
                    </p>
                </div>

                <div class="text-right">
                    <h2 class="text-4xl font-bold text-emerald-500">
                        {{
                            readingProgress
                        }}%
                    </h2>
                </div>
            </div>

            <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200">
                <div class="h-full rounded-full bg-emerald-500 transition-all duration-300" :style="{
                    width:
                        readingProgress +
                        '%',
                }" />
            </div>

            <p class="mt-3 text-sm text-slate-500">
                Teruskan membaca sampai
                selesai untuk membuka
                tahap berikutnya.
            </p>
        </section>

        <!-- MATERI -->
        <section class="rounded-3xl bg-white p-6 shadow-sm">
            <div ref="pdfContainer"
                class="flex flex-col items-center h-[680px] overflow-y-scroll rounded-2xl border border-slate-200 bg-slate-100 p-5" />
        </section>

        <!-- REFLECTION -->
        <section " class=" rounded-3xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        💭 Refleksi
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Isi refleksi sebelum
                        melanjutkan ke kuis.
                    </p>
                </div>

                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                    Wajib diisi
                </span>
            </div>

            <!-- UNLOCKED -->
            <div v-if="reflectionUnlocked" class="space-y-5">
                <div v-for="(question, index) in material.reflection_questions" :key="index">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        {{ index + 1 }}. {{ question }}
                    </label>

                    <textarea v-model="reflectionAnswers[index]" rows="4"
                        class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500" />
                </div>

                <div class="flex justify-end">
                    <button :disabled="!reflectionCompleted
                        ||
                        readingProgress < 70
                        " @click="saveReflection" :class="reflectionCompleted
                            &&
                            readingProgress >= 70
                            ? 'bg-purple-600 hover:bg-purple-700'
                            : 'cursor-not-allowed bg-slate-200 text-slate-500'
                            " class="cursor-pointer rounded-2xl px-6 py-3 text-sm font-semibold text-white transition">
                        {{
                            reflectionSaved
                                ? '✅ Refleksi Tersimpan'
                                : 'Simpan Refleksi'
                        }}
                    </button>
                </div>
            </div>
            <!-- LOCKED STATE -->
            <div v-else class="space-y-5">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <div class="flex items-start gap-4">
                        <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                            🔒
                        </div>

                        <div>
                            <h3 class="font-semibold text-amber-800">
                                Refleksi Belum
                                Terbuka
                            </h3>

                            <p class="mt-1 text-sm text-amber-700">
                                Isi pertanyaan
                                pemantik terlebih
                                dahulu untuk
                                membuka sesi
                                refleksi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SKELETON -->
                <div class="animate-pulse space-y-5">
                    <div v-for="(_, index) in material.reflection_questions" :key="index" class="space-y-3">
                        <!-- TITLE -->
                        <div class="h-5 w-1/3 rounded bg-slate-200" />

                        <!-- TEXTAREA -->
                        <div class="h-28 rounded-2xl bg-slate-100" />
                    </div>
                </div>
            </div>
            <!-- INFO -->
            <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700">
                🔒 Kuis akan terbuka
                setelah refleksi selesai
                diisi.
            </div>
        </section>

        <!-- DOWNLOAD PDF -->
        <section class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100">
                        <Download class="h-7 w-7 text-red-500" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Unduh Materi
                            (PDF)
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Unduh versi PDF
                            materi untuk
                            dipelajari secara
                            offline atau
                            dicetak.
                        </p>
                    </div>
                </div>

                <a v-if="material.pdf_url" :href="material.pdf_url" target="_blank"
                    class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
                    <Download class="h-5 w-5" />

                    Unduh PDF
                </a>
            </div>
        </section>

        <!-- FOOTER -->
        <section class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <button
                class="rounded-2xl border border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                ← Kembali ke Pertemuan
            </button>

            <!-- NEXT -->
            <button :disabled="!canProceed
                " :class="canProceed
                    ? 'bg-emerald-500 hover:bg-emerald-600'
                    : 'cursor-not-allowed bg-slate-300 text-slate-500'
                    " class="rounded-2xl px-6 py-3 font-semibold text-white transition">
                {{
                    canProceed
                        ? 'Lanjut ke Kuis →'
                        : '🔒 Selesaikan Refleksi'
                }}
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