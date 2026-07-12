<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { login } from '@/routes';

defineOptions({
    layout: undefined,
});

const classOptions = [
    'X TKJ-T-1',
    'X TKJ-T-2',
    'XI TKJ-T-1',
    'XI TKJ-T-2',
    'XII TKJ-T-1',
    'XII TKJ-T-2',
];

const form = useForm({
    name: '',
    class: classOptions[0],
    username: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        preserveScroll: true,
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Daftar Akun" />

    <div class="min-h-screen bg-slate-100">
        <div class="grid min-h-screen lg:grid-cols-2">
            <!-- LEFT -->
            <div class="relative hidden overflow-hidden bg-linear-to-br from-sky-950 via-blue-900 to-teal-700 lg:flex">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12),transparent_40%)]" />

                <div
                    class="relative z-10 flex w-full flex-col items-center justify-center px-12 text-center text-white">
                    <h1 class="text-5xl font-black leading-tight">
                        Bit-Learn
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-relaxed text-sky-100">
                        Daftarkan akun siswa untuk mulai belajar IoT dan Micro:Bit.
                    </p>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center justify-center bg-white px-5 py-6 lg:px-8">
                <div class="w-full max-w-md rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
                    <!-- MOBILE BRAND -->
                    <div class="mb-10 text-center lg:hidden">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-linear-to-br from-cyan-500 to-teal-500 text-2xl font-black text-white shadow-lg">
                            IoT
                        </div>

                        <h1 class="mt-5 text-2xl font-black text-slate-800">
                            LMS IoT
                        </h1>
                    </div>

                    <!-- HEADER -->
                    <div class="mb-8">
                        <h2 class="mt-2 text-2xl font-black text-slate-900">
                            Daftar Akun Siswa
                        </h2>

                        <p class="mt-2 text-sm leading-relaxed text-slate-500">
                            Buat akun untuk mulai mengakses materi, kuis, dan praktik.
                        </p>
                    </div>

                    <!-- GLOBAL ERROR -->
                    <div v-if="form.hasErrors"
                        class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                        <div v-for="(error, key) in form.errors" :key="key">
                            {{ error }}
                        </div>
                    </div>

                    <!-- FORM -->
                    <form class="space-y-4" @submit.prevent="submit">
                        <!-- NAME -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Nama Lengkap
                            </label>

                            <input v-model="form.name" type="text" placeholder="Contoh: Andi Pratama" :class="[
                                'w-full rounded-2xl border bg-white px-4 py-3 text-slate-800 placeholder:text-slate-400 outline-none transition focus:ring-4',
                                form.errors.name
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                    : 'border-slate-200 focus:border-teal-500 focus:ring-teal-100'
                            ]" />
                        </div>

                        <!-- CLASS -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Kelas
                            </label>

                            <select v-model="form.class" :class="[
                                'w-full rounded-2xl border bg-white px-4 py-3 text-slate-800 outline-none transition focus:ring-4',
                                form.errors.class
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                    : 'border-slate-200 focus:border-teal-500 focus:ring-teal-100'
                            ]">
                                <option v-for="item in classOptions" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <!-- USERNAME -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Username
                            </label>

                            <input v-model="form.username" type="text" placeholder="contoh.username" :class="[
                                'w-full rounded-2xl border bg-white px-4 py-3 text-slate-800 placeholder:text-slate-400 outline-none transition focus:ring-4',
                                form.errors.username
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                    : 'border-slate-200 focus:border-teal-500 focus:ring-teal-100'
                            ]" />
                        </div>

                        <!-- PASSWORD -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Password
                            </label>

                            <input v-model="form.password" type="password" autocomplete="new-password"
                                placeholder="••••••••" :class="[
                                    'w-full rounded-2xl border bg-white px-4 py-3 text-slate-800 placeholder:text-slate-400 outline-none transition focus:ring-4',
                                    form.errors.password
                                        ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                        : 'border-slate-200 focus:border-teal-500 focus:ring-teal-100'
                                ]" />
                        </div>

                        <!-- PASSWORD CONFIRMATION -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Konfirmasi Password
                            </label>

                            <input v-model="form.password_confirmation" type="password" autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-800 placeholder:text-slate-400 outline-none transition focus:border-teal-500 focus:ring-4 focus:ring-teal-100" />
                        </div>

                        <!-- BUTTON -->
                        <button type="submit" :disabled="form.processing"
                            class="w-full rounded-2xl bg-linear-to-r from-blue-600 to-cyan-500 px-5 py-3.5 font-semibold text-white shadow-lg transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-70">
                            {{
                                form.processing
                                    ? 'Memproses...'
                                    : 'Daftar Sekarang'
                            }}
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-500">
                        Sudah punya akun?
                        <Link :href="login()" class="font-semibold text-teal-600 hover:underline">
                            Masuk di sini
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
