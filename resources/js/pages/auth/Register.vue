<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { login } from '@/routes';

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    },
});
</script>

<template>
    <Head title="Register" />
    <div class="mx-auto flex min-h-[70vh] w-full max-w-2xl items-center justify-center px-6">
        <div class="w-full rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-wider text-teal-600">
                Registration disabled
            </p>

            <h1 class="mt-3 text-3xl font-black text-slate-900">
                Akun baru hanya dibuat oleh admin/guru
            </h1>

            <p class="mt-4 text-slate-600">
                Fitur registrasi mandiri memang dimatikan di aplikasi ini, jadi page ini hanya menampilkan informasi agar build tetap aman.
            </p>

            <div class="mt-6">
                <Link
                    :href="login()"
                    class="inline-flex items-center rounded-2xl bg-linear-to-r from-blue-600 to-cyan-500 px-5 py-3 font-bold text-white shadow-lg transition hover:scale-[1.01]"
                >
                    Kembali ke login
                </Link>
            </div>
        </div>
    </div>
</template>
