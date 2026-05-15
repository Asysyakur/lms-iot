<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: undefined,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {

    console.log('CLICK LOGIN');

    form.post('/login', {

        preserveScroll: true,

        onStart: () => {
            console.log('START');
        },

        onSuccess: () => {
            console.log('SUCCESS');
        },

        onError: (errors) => {
            console.log(errors);
        },

        onFinish: () => {
            console.log('FINISH');
        },
    });
};

</script>

<template>

    <Head title="Login" />

    <div class="min-h-screen bg-slate-100">
        <div class="grid min-h-screen lg:grid-cols-2">

            <!-- LEFT -->
            <div
                class="relative hidden overflow-hidden bg-linear-to-br from-sky-950 via-blue-900 to-teal-700 lg:flex">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12),transparent_40%)]" />

                <div
                    class="relative z-10 flex w-full flex-col items-center justify-center px-16 text-center text-white">
                    <div class="mb-8 flex h-24 w-24 items-center justify-center rounded-3xl bg-white/10 backdrop-blur">
                        <span class="text-4xl font-black">
                            IoT
                        </span>
                    </div>

                    <h1 class="text-5xl font-black leading-tight">
                        Learning Management System
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-sky-100">
                        Platform pembelajaran interaktif
                        berbasis IoT dan Micro:bit
                        untuk siswa dan guru.
                    </p>

                    <div class="mt-10 flex flex-wrap justify-center gap-3">
                        <div class="rounded-full border border-white/20 bg-white/10 px-5 py-2 text-sm backdrop-blur">
                            🚀 Interactive Learning
                        </div>

                        <div class="rounded-full border border-white/20 bg-white/10 px-5 py-2 text-sm backdrop-blur">
                            📚 LMS IoT
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center justify-center bg-white px-6 py-10">
                <div class="w-full max-w-md">

                    <!-- MOBILE BRAND -->
                    <div class="mb-10 text-center lg:hidden">
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-linear-to-br from-cyan-500 to-teal-500 text-3xl font-black text-white shadow-lg">
                            IoT
                        </div>

                        <h1 class="mt-5 text-3xl font-black text-slate-800">
                            LMS IoT
                        </h1>
                    </div>

                    <!-- HEADER -->
                    <div class="mb-8">
                        <p class="text-sm font-semibold uppercase tracking-wider text-teal-600">
                            Learning Management System
                        </p>

                        <h2 class="mt-3 text-4xl font-black text-slate-900">
                            Selamat Datang 👋
                        </h2>

                        <p class="mt-3 text-slate-500">
                            Login menggunakan akun yang
                            telah didaftarkan oleh guru/admin.
                        </p>
                    </div>

                    <!-- GLOBAL ERROR -->
                    <div v-if="form.hasErrors" class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                        <div v-for="(error, key) in form.errors" :key="key">
                            {{ error }}
                        </div>
                    </div>

                    <!-- FORM -->
                    <form class="space-y-5" @submit.prevent="submit">

                        <!-- EMAIL -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Email / Username
                            </label>

                            <input v-model="form.email" type="text" :class="[
                                'w-full rounded-2xl border bg-white px-5 py-4 text-slate-800 placeholder:text-slate-400 outline-none transition focus:ring-4',
                                form.errors.email
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                    : 'border-slate-200 focus:border-teal-500 focus:ring-teal-100'
                            ]" placeholder="contoh@smk.sch.id" />

                            <p v-if="form.errors.email" class="mt-2 text-sm font-medium text-red-500">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- PASSWORD -->
                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label class="text-sm font-semibold text-slate-700">
                                    Password
                                </label>
                            </div>

                            <input v-model="form.password" type="password" autocomplete="current-password" :class="[
                                'w-full rounded-2xl border bg-white px-5 py-4 text-slate-800 placeholder:text-slate-400 outline-none transition focus:ring-4',
                                form.errors.password
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                    : 'border-slate-200 focus:border-teal-500 focus:ring-teal-100'
                            ]" placeholder="••••••••" />

                            <p v-if="form.errors.password" class="mt-2 text-sm font-medium text-red-500">
                                {{ form.errors.password }}
                            </p>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        </div>

                        <!-- REMEMBER -->
                        <label class="flex items-center gap-3 text-sm text-slate-600">
                            <input v-model="form.remember" type="checkbox"
                                class="rounded border-slate-300 text-teal-600" />

                            Ingat saya
                        </label>

                        <!-- BUTTON -->
                        <button type="submit" :disabled="form.processing"
                            class="w-full rounded-2xl bg-linear-to-r from-blue-600 to-cyan-500 px-5 py-4 font-bold text-white shadow-lg transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-70">
                            {{
                                form.processing
                                    ? 'Memproses...'
                            : '🚀 Masuk ke Sistem'
                            }}
                        </button>
                    </form>

                    <!-- FOOTER -->
                    <div
                        class="mt-10 rounded-2xl border border-amber-100 bg-amber-50 p-4 text-sm leading-relaxed text-amber-700">
                        ⚠️ Akun siswa dan guru hanya dapat dibuat oleh admin/guru.
                        Tidak tersedia fitur registrasi mandiri.
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>