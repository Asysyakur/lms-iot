<!-- resources/js/pages/student/meetings/materials/Show.vue -->

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';

import StudentSidebarLayout from '@/layouts/student/StudentSidebarLayout.vue';

import {
    BookOpen,
    Download,
    CheckCircle2,
    Lock,
} from 'lucide-vue-next';

defineOptions({
    layout: StudentSidebarLayout,
});

const materialContent = ref<HTMLElement | null>(
    null,
);

const readingProgress = ref(0);

const reflectionAnswers = ref({
    answer1: '',
    answer2: '',
    answer3: '',
});

const reflectionCompleted = computed(() => {
    return (
        reflectionAnswers.value.answer1.trim() &&
        reflectionAnswers.value.answer2.trim() &&
        reflectionAnswers.value.answer3.trim()
    );
});

const updateProgress = () => {
    if (!materialContent.value) return;

    const element = materialContent.value;

    const scrollTop = element.scrollTop;

    const scrollHeight =
        element.scrollHeight -
        element.clientHeight;

    const progress =
        (scrollTop / scrollHeight) * 100;

    readingProgress.value = Math.min(
        100,
        Math.max(0, Math.round(progress)),
    );
};

onMounted(() => {
    updateProgress();
});
</script>

<template>
    <div class="space-y-6">
        <!-- HEADER -->
        <section
            class="relative overflow-hidden rounded-3xl bg-[#173B74] p-6 text-white shadow-lg"
        >
            <div
                class="absolute right-0 top-0 h-40 w-40 rounded-full bg-cyan-400/10"
            />

            <div>
                <span
                    class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700"
                >
                    Pertemuan 1
                </span>

                <h1
                    class="mt-4 text-3xl font-bold"
                >
                    📘 Pengenalan IoT &
                    Micro:bit dengan Python
                </h1>

                <p
                    class="mt-2 max-w-3xl text-slate-300"
                >
                    Pelajari konsep dasar
                    Internet of Things,
                    sensor, dan penggunaan
                    Micro:bit menggunakan
                    Python.
                </p>
            </div>
        </section>

        <!-- PERTANYAAN PEMANTIK -->
        <section
            class="rounded-3xl bg-white p-6 shadow-sm"
        >
            <div
                class="mb-6 flex items-center justify-between"
            >
                <div
                    class="flex items-center gap-3"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100"
                    >
                        <BookOpen
                            class="h-5 w-5 text-purple-600"
                        />
                    </div>

                    <div>
                        <h2
                            class="font-bold text-slate-800"
                        >
                            Pertanyaan
                            Pemantik
                        </h2>

                        <p
                            class="text-sm text-slate-500"
                        >
                            Jawablah
                            pertanyaan
                            berikut sebelum
                            membaca materi.
                        </p>
                    </div>
                </div>

                <span
                    class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"
                >
                    Wajib diisi
                </span>
            </div>

            <div class="space-y-5">
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Menurutmu,
                        bagaimana cara
                        kerja sensor pada
                        perangkat
                        elektronik di
                        sekitarmu?
                    </label>

                    <textarea
                        rows="5"
                        placeholder="Tulis jawabanmu di sini..."
                        class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500"
                    />
                </div>

                <div
                    class="flex justify-end"
                >
                    <button
                        class="rounded-2xl bg-purple-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-purple-700"
                    >
                        Simpan Jawaban
                    </button>
                </div>
            </div>
        </section>

        <!-- READING PROGRESS -->
        <section
            class="rounded-3xl bg-white p-6 shadow-sm"
        >
            <div
                class="flex items-center justify-between"
            >
                <div>
                    <h2
                        class="font-bold text-slate-800"
                    >
                        📖 Progress Membaca
                    </h2>

                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        Kamu telah membaca
                    </p>
                </div>

                <div
                    class="text-right"
                >
                    <h2
                        class="text-4xl font-bold text-emerald-500"
                    >
                        {{
                            readingProgress
                        }}%
                    </h2>
                </div>
            </div>

            <div
                class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200"
            >
                <div
                    class="h-full rounded-full bg-emerald-500 transition-all duration-300"
                    :style="{
                        width:
                            readingProgress +
                            '%',
                    }"
                />
            </div>

            <p
                class="mt-3 text-sm text-slate-500"
            >
                Teruskan membaca sampai
                selesai untuk membuka
                tahap berikutnya.
            </p>
        </section>

        <!-- MATERI -->
        <section
            class="rounded-3xl bg-white p-6 shadow-sm"
        >
            <div
                ref="materialContent"
                class="max-h-[700px] space-y-10 overflow-y-auto pr-3"
                @scroll="updateProgress"
            >
                <!-- SECTION -->
                <div>
                    <h2
                        class="text-xl font-bold text-slate-800"
                    >
                        A. Pengertian Sensor
                    </h2>

                    <div
                        class="mt-5 space-y-5 text-[15px] leading-relaxed text-slate-600"
                    >
                        <p>
                            Sensor adalah
                            perangkat yang
                            digunakan untuk
                            mendeteksi
                            perubahan di
                            lingkungan
                            sekitar dan
                            mengubahnya
                            menjadi sinyal
                            listrik.
                        </p>

                        <p>
                            Pada Micro:bit,
                            sensor
                            memungkinkan
                            perangkat untuk
                            menerima input
                            dari dunia
                            nyata sehingga
                            dapat diproses
                            dan menghasilkan
                            output yang
                            sesuai.
                        </p>

                        <div
                            class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-700"
                        >
                            💡 IoT akan
                            terus memakai
                            input sensor
                            dalam sistem
                            otomatisasi.
                        </div>
                    </div>
                </div>

                <!-- SECTION -->
                <div>
                    <h2
                        class="text-xl font-bold text-slate-800"
                    >
                        B. Jenis Sensor pada
                        Micro:bit
                    </h2>

                    <div
                        class="mt-5 space-y-4"
                    >
                        <div
                            class="rounded-2xl border border-slate-200 p-5"
                        >
                            <h3
                                class="font-bold text-slate-800"
                            >
                                🌡️ Sensor
                                Cahaya
                            </h3>

                            <p
                                class="mt-2 text-sm leading-relaxed text-slate-600"
                            >
                                Digunakan
                                untuk
                                mendeteksi
                                intensitas
                                cahaya.
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-slate-200 p-5"
                        >
                            <h3
                                class="font-bold text-slate-800"
                            >
                                🌡️ Sensor
                                Suhu
                            </h3>

                            <p
                                class="mt-2 text-sm leading-relaxed text-slate-600"
                            >
                                Mengukur suhu
                                lingkungan
                                sekitar.
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-slate-200 p-5"
                        >
                            <h3
                                class="font-bold text-slate-800"
                            >
                                🧭 Accelerometer
                            </h3>

                            <p
                                class="mt-2 text-sm leading-relaxed text-slate-600"
                            >
                                Mendeteksi
                                gerakan dan
                                orientasi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- DUMMY LONG -->
                <div
                    class="space-y-5 text-[15px] leading-relaxed text-slate-600"
                >
                    <p
                        v-for="i in 15"
                        :key="i"
                    >
                        Internet of Things
                        memungkinkan
                        perangkat untuk
                        saling terhubung dan
                        bertukar data secara
                        otomatis melalui
                        jaringan internet.
                        Micro:bit dapat
                        digunakan untuk
                        membuat berbagai
                        proyek IoT sederhana
                        menggunakan sensor
                        dan Python.
                    </p>
                </div>
            </div>
        </section>

        <!-- REFLECTION -->
        <section
            class="rounded-3xl bg-white p-6 shadow-sm"
        >
            <div
                class="mb-6 flex items-center justify-between"
            >
                <div>
                    <h2
                        class="text-lg font-bold text-slate-800"
                    >
                        💭 Refleksi
                    </h2>

                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        Isi refleksi sebelum
                        melanjutkan ke kuis.
                    </p>
                </div>

                <span
                    class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"
                >
                    Wajib diisi
                </span>
            </div>

            <div class="space-y-5">
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        1. Apa yang kamu
                        pahami tentang
                        sensor pada
                        Micro:bit?
                    </label>

                    <textarea
                        v-model="
                            reflectionAnswers.answer1
                        "
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500"
                    />
                </div>

                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        2. Sebutkan contoh
                        penerapan sensor
                        dalam kehidupan
                        sehari-hari.
                    </label>

                    <textarea
                        v-model="
                            reflectionAnswers.answer2
                        "
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500"
                    />
                </div>

                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        3. Menurutmu,
                        sensor apa yang
                        paling bermanfaat?
                    </label>

                    <textarea
                        v-model="
                            reflectionAnswers.answer3
                        "
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 p-4 outline-none transition focus:border-blue-500"
                    />
                </div>

                <div
                    class="flex justify-end"
                >
                    <button
                        :disabled="
                            !reflectionCompleted
                        "
                        :class="
                            reflectionCompleted
                                ? 'bg-purple-600 hover:bg-purple-700'
                                : 'cursor-not-allowed bg-slate-200 text-slate-500'
                        "
                        class="rounded-2xl px-6 py-3 text-sm font-semibold text-white transition"
                    >
                        Simpan Refleksi
                    </button>
                </div>
            </div>

            <!-- INFO -->
            <div
                class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700"
            >
                🔒 Kuis akan terbuka
                setelah refleksi selesai
                diisi.
            </div>
        </section>

        <!-- DOWNLOAD PDF -->
        <section
            class="rounded-3xl bg-white p-6 shadow-sm"
        >
            <div
                class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
            >
                <div
                    class="flex items-start gap-4"
                >
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100"
                    >
                        <Download
                            class="h-7 w-7 text-red-500"
                        />
                    </div>

                    <div>
                        <h2
                            class="text-lg font-bold text-slate-800"
                        >
                            Unduh Materi
                            (PDF)
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-500"
                        >
                            Unduh versi PDF
                            materi untuk
                            dipelajari secara
                            offline atau
                            dicetak.
                        </p>
                    </div>
                </div>

                <button
                    class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                >
                    <Download
                        class="h-5 w-5"
                    />

                    Unduh PDF
                </button>
            </div>
        </section>

        <!-- FOOTER -->
        <section
            class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
        >
            <button
                class="rounded-2xl border border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                ← Kembali ke Pertemuan
            </button>

            <!-- NEXT -->
            <button
                :disabled="
                    !reflectionCompleted
                "
                :class="
                    reflectionCompleted
                        ? 'bg-emerald-500 hover:bg-emerald-600'
                        : 'cursor-not-allowed bg-slate-300 text-slate-500'
                "
                class="rounded-2xl px-6 py-3 font-semibold text-white transition"
            >
                {{
                    reflectionCompleted
                        ? 'Lanjut ke Kuis →'
                        : '🔒 Selesaikan Refleksi'
                }}
            </button>
        </section>
    </div>
</template>