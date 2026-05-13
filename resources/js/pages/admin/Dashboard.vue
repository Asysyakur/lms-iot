<script setup lang="ts">
const stats = [
    {
        title: "Pre-test",
        value: 90,
        status: "Tuntas",
        color: "bg-blue-50 text-blue-600",
    },
    {
        title: "Rata-rata Kuis",
        value: 85,
        status: "≥ 80",
        color: "bg-purple-50 text-purple-600",
    },
    {
        title: "Pertemuan Selesai",
        value: "3/8",
        status: "Selesai",
        color: "bg-emerald-50 text-emerald-600",
    },
    {
        title: "Nilai Akhir",
        value: 78,
        status: "Proses",
        color: "bg-amber-50 text-amber-600",
    },
];

const activities = [
    {
        title: "Menyelesaikan pre-test",
        status: "Selesai",
    },
    {
        title: "Membaca materi pertemuan 1",
        status: "Selesai",
    },
    {
        title: "Mengisi refleksi pertemuan 2",
        status: "Selesai",
    },
    {
        title: "Mengerjakan kuis pertemuan 2",
        status: "Nilai 85",
    },
];

const scores = [
    {
        label: "Pre-test",
        value: 90,
        color: "bg-blue-500",
    },
    {
        label: "Kuis P1",
        value: 88,
        color: "bg-emerald-500",
    },
    {
        label: "Kuis P2",
        value: 85,
        color: "bg-purple-500",
    },
    {
        label: "LKPD",
        value: 78,
        color: "bg-orange-500",
    },
];
</script>

<template>
    <div class="min-h-screen bg-slate-100">
        <div class="flex">
            <!-- SIDEBAR -->
            <aside
                class="flex min-h-screen w-72 flex-col bg-[#0E2A5A] text-white"
            >
                <div
                    class="flex items-center gap-3 border-b border-white/10 p-6"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 font-bold"
                    >
                        IoT
                    </div>

                    <div>
                        <h1 class="text-lg font-bold">LMS Blended Learning</h1>
                        <p class="text-sm text-slate-300">
                            IoT Micro:bit — Kelas XI TKJ
                        </p>
                    </div>
                </div>

                <div class="flex-1 space-y-8 p-4">
                    <div>
                        <p
                            class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400"
                        >
                            Menu Utama
                        </p>

                        <nav class="space-y-2">
                            <button
                                class="flex w-full items-center gap-3 rounded-xl bg-white/10 px-4 py-3 text-left transition hover:bg-white/20"
                            >
                                <span>🏠</span>
                                <span>Beranda</span>
                            </button>

                            <button
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-slate-300 transition hover:bg-white/10"
                            >
                                <span>📚</span>
                                <span>Pertemuan</span>
                            </button>

                            <button
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-slate-300 transition hover:bg-white/10"
                            >
                                <span>📝</span>
                                <span>Kuis</span>
                            </button>

                            <button
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-slate-300 transition hover:bg-white/10"
                            >
                                <span>🛠️</span>
                                <span>Praktik / LKPD</span>
                            </button>

                            <button
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-slate-300 transition hover:bg-white/10"
                            >
                                <span>📊</span>
                                <span>Nilai</span>
                            </button>

                            <button
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-slate-300 transition hover:bg-white/10"
                            >
                                <span>🕘</span>
                                <span>Riwayat Belajar</span>
                            </button>
                        </nav>
                    </div>

                    <div>
                        <p
                            class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400"
                        >
                            Akun
                        </p>

                        <button
                            class="flex w-full items-center gap-3 rounded-xl bg-emerald-500 px-4 py-3 font-semibold"
                        >
                            👤 Profil Saya
                        </button>
                    </div>
                </div>
            </aside>

            <!-- CONTENT -->
            <main class="flex-1 p-6">
                <!-- HEADER -->
                <section
                    class="relative overflow-hidden rounded-3xl bg-[#173B74] p-6 text-white shadow-lg"
                >
                    <div
                        class="absolute right-0 top-0 h-40 w-40 rounded-full bg-cyan-400/10"
                    />
                    <div
                        class="absolute bottom-0 right-20 h-24 w-24 rounded-full bg-emerald-400/10"
                    />

                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-5">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-500 text-3xl font-bold"
                            >
                                A
                            </div>

                            <div>
                                <div class="mb-2 flex items-center gap-2">
                                    <span
                                        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                    >
                                        Profil Siswa
                                    </span>

                                    <span
                                        class="rounded-full bg-lime-100 px-3 py-1 text-xs font-semibold text-lime-700"
                                    >
                                        ● Aktif Belajar
                                    </span>
                                </div>

                                <h1 class="text-4xl font-bold">
                                    Andi Pratama
                                </h1>

                                <p class="mt-1 text-slate-300">
                                    Kelas X TKJ-1 &nbsp; | &nbsp; NIS 10241007
                                    &nbsp; | &nbsp; Semester Genap 2024/2025
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span
                                        class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700"
                                    >
                                        Pertemuan aktif: Sensor dan Input
                                    </span>

                                    <span
                                        class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"
                                    >
                                        Rata-rata Kuis: 85
                                    </span>

                                    <span
                                        class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700"
                                    >
                                        Progress 65%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <h2 class="text-5xl font-bold text-cyan-300">
                                65%
                            </h2>

                            <p class="mt-2 text-sm text-slate-300">
                                Progress Pembelajaran
                            </p>

                            <div
                                class="mt-3 h-3 w-52 overflow-hidden rounded-full bg-white/20"
                            >
                                <div
                                    class="h-full w-[65%] rounded-full bg-cyan-400"
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- GRID -->
                <div class="mt-6 grid grid-cols-12 gap-6">
                    <!-- IDENTITAS -->
                    <div
                        class="col-span-12 rounded-3xl bg-white p-6 shadow-sm xl:col-span-4"
                    >
                        <h2 class="text-lg font-bold text-slate-800">
                            Identitas Siswa
                        </h2>

                        <p class="mb-6 text-sm text-slate-500">
                            Data utama peserta didik
                        </p>

                        <div class="space-y-5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500"
                                    >Nama Lengkap</span
                                >
                                <span class="font-semibold text-slate-800"
                                    >Andi Pratama</span
                                >
                            </div>

                            <div class="flex justify-between">
                                <span class="text-slate-500">Kelas</span>
                                <span class="font-semibold text-slate-800"
                                    >X TKJ-1</span
                                >
                            </div>

                            <div class="flex justify-between">
                                <span class="text-slate-500">NIS</span>
                                <span class="font-semibold text-slate-800"
                                    >10241007</span
                                >
                            </div>

                            <div class="flex justify-between">
                                <span class="text-slate-500"
                                    >Program Keahlian</span
                                >
                                <span class="font-semibold text-slate-800">
                                    Teknik Jaringan Komputer dan Telekomunikasi
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-slate-500">Status</span>

                                <span
                                    class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                >
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- RINGKASAN -->
                    <div
                        class="col-span-12 rounded-3xl bg-white p-6 shadow-sm xl:col-span-8"
                    >
                        <h2 class="text-lg font-bold text-slate-800">
                            Ringkasan Pembelajaran
                        </h2>

                        <p class="mb-6 text-sm text-slate-500">
                            Statistik berdasarkan aktivitas dan nilai terbaru
                        </p>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div
                                v-for="(item, index) in stats"
                                :key="index"
                                :class="item.color"
                                class="rounded-2xl p-5"
                            >
                                <div
                                    class="mb-4 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm font-bold"
                                >
                                    {{ index + 1 }}
                                </div>

                                <h3 class="text-4xl font-bold">
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
                    <div
                        class="col-span-12 rounded-3xl bg-white p-6 shadow-sm xl:col-span-6"
                    >
                        <h2 class="text-lg font-bold text-slate-800">
                            Statistik Nilai
                        </h2>

                        <p class="mb-6 text-sm text-slate-500">
                            Rekap nilai berdasarkan komponen pembelajaran
                        </p>

                        <div class="space-y-5">
                            <div
                                v-for="(item, index) in scores"
                                :key="index"
                            >
                                <div
                                    class="mb-2 flex items-center justify-between text-sm"
                                >
                                    <span class="font-medium text-slate-600">
                                        {{ item.label }}
                                    </span>

                                    <span class="font-bold text-slate-800">
                                        {{ item.value }}
                                    </span>
                                </div>

                                <div
                                    class="h-3 overflow-hidden rounded-full bg-slate-200"
                                >
                                    <div
                                        :class="item.color"
                                        class="h-full rounded-full"
                                        :style="{
                                            width: item.value + '%',
                                        }"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700"
                        >
                            Nilai minimum kuis adalah 80. Jika belum mencapai
                            batas, siswa dapat mengulang kuis.
                        </div>
                    </div>

                    <!-- AKTIVITAS -->
                    <div
                        class="col-span-12 rounded-3xl bg-white p-6 shadow-sm xl:col-span-6"
                    >
                        <h2 class="text-lg font-bold text-slate-800">
                            Progress Aktivitas
                        </h2>

                        <p class="mb-6 text-sm text-slate-500">
                            Capaian aktivitas belajar siswa
                        </p>

                        <div class="space-y-4">
                            <div
                                v-for="(activity, index) in activities"
                                :key="index"
                                class="flex items-center justify-between rounded-2xl bg-slate-50 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                                    >
                                        ✓
                                    </div>

                                    <span
                                        class="font-medium text-slate-700"
                                    >
                                        {{ activity.title }}
                                    </span>
                                </div>

                                <span
                                    class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                >
                                    {{ activity.status }}
                                </span>
                            </div>
                        </div>

                        <button
                            class="mt-6 w-full rounded-2xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700"
                        >
                            Lihat Riwayat Nilai Lengkap
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>