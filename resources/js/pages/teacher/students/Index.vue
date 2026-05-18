<!-- resources/js/pages/teacher/students/Index.vue -->

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';
import Swal from 'sweetalert2';
import {
  UserPlus,
  Trash2,
  UserCheck,
  UserX,
} from 'lucide-vue-next';

defineOptions({
  layout: TeacherSidebarLayout,
});

interface Student {
  id: number;
  name: string;
  class: string;
  username: string;
  password: string;
  active: boolean;
}

const props = defineProps<{
  students: Student[];
}>();

const form = useForm({
  name: '',
  class: 'XI TKJ-T-1',
  username: '',
  password: '',
});

const deletedStudents = ref(0);

const totalStudents = computed(() => {
  return props.students.length;
});

const activeStudents = computed(() => {
  return props.students.filter(
    (student: Student) => student.active,
  ).length;
});

const inactiveStudents = computed(() => {
  return props.students.filter(
    (student: Student) => !student.active,
  ).length;
});

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
});

const addStudent = () => {
  form.post('/teacher/students', {

    preserveScroll: true,

    onSuccess: () => {

      Toast.fire({
        icon: 'success',
        title: 'Akun siswa berhasil dibuat',
      });

      form.reset();

      form.class = 'XI TKJ-T-1';
    },

    onError: () => {

      Toast.fire({
        icon: 'error',
        title: 'Gagal membuat akun siswa',
      });
    },
  });
};

const removeStudent = async (id: number) => {

  const result = await Swal.fire({
    title: 'Hapus akun?',
    text: 'Data siswa akan dihapus permanen',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#ef4444',
  });

  if (!result.isConfirmed) {
    return;
  }

  router.delete(`/teacher/students/${id}`, {

    preserveScroll: true,

    onSuccess: () => {

      deletedStudents.value++;

      Toast.fire({
        icon: 'success',
        title: 'Akun siswa berhasil dihapus',
      });
    },

    onError: () => {

      Toast.fire({
        icon: 'error',
        title: 'Gagal menghapus akun',
      });
    },
  });
};
</script>

<template>
  <div class="space-y-8">
    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#173B74] via-[#114084] to-emerald-500 p-8 text-white shadow-xl">
      <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-white/10" />

      <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <div class="mb-4 inline-flex rounded-full bg-white/20 px-4 py-1 text-xs font-bold uppercase tracking-wider">
            Manajemen Siswa
          </div>

          <h1 class="text-4xl font-black">
            👨‍🎓 Kelola Akun Siswa
          </h1>

          <p class="mt-3 max-w-3xl text-lg text-slate-200">
            Guru dapat membuat akun siswa,
            mengatur username/password,
            serta menghapus akun yang tidak
            diperlukan.
          </p>
        </div>
      </div>
    </section>

    <!-- TOP -->
    <section class="grid gap-6 xl:grid-cols-12">
      <!-- FORM -->
      <div class="rounded-3xl bg-white p-6 shadow-sm xl:col-span-7">
        <div class="mb-6 flex items-center gap-4">
          <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100">
            <UserPlus class="h-7 w-7 text-emerald-600" />
          </div>

          <div>
            <h2 class="text-xl font-bold text-slate-800">
              Form Tambah Akun
              Siswa
            </h2>

            <p class="mt-1 text-sm text-slate-500">
              Buat username dan password
              untuk akun siswa baru.
            </p>
          </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
          <!-- NAMA -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
              Nama Siswa
            </label>

            <input v-model="form.name" type="text" placeholder="Contoh: Andi Pratama"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-emerald-500" />
          </div>

          <!-- KELAS -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
              Kelas
            </label>

            <select v-model="form.class"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-emerald-500">
              <option>
                XI TKJ-T-1
              </option>

              <option>
                XI TKJ-T-2
              </option>
            </select>
          </div>

          <!-- USERNAME -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
              Username
            </label>

            <input v-model="form.username" type="text" placeholder="contoh.username"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-emerald-500" />
          </div>

          <!-- PASSWORD -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
              Password Awal
            </label>

            <input v-model="form.password" type="text" placeholder="password123"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-emerald-500" />
          </div>
        </div>

        <!-- BUTTON -->
        <div class="mt-6 flex justify-end">
          <button @click="addStudent" :disabled="form.processing"
            class="cursor-pointer inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
            <UserPlus class="h-5 w-5" />

            Simpan Akun Siswa
          </button>
        </div>
      </div>

      <!-- STATS -->
      <div class="space-y-6 xl:col-span-5">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
          <h2 class="mb-6 text-lg font-bold text-slate-800">
            Ringkasan Akun
          </h2>

          <div class="grid grid-cols-2 gap-4">
            <!-- TOTAL -->
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
              <p class="text-sm font-semibold text-blue-600">
                Total Akun
              </p>

              <h2 class="mt-2 text-4xl font-black text-blue-700">
                {{ totalStudents }}
              </h2>
            </div>

            <!-- ACTIVE -->
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
              <p class="text-sm font-semibold text-emerald-600">
                Aktif Login
              </p>

              <h2 class="mt-2 text-4xl font-black text-emerald-700">
                {{ activeStudents }}
              </h2>
            </div>

            <!-- INACTIVE -->
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
              <p class="text-sm font-semibold text-amber-600">
                Belum Login
              </p>

              <h2 class="mt-2 text-4xl font-black text-amber-700">
                {{ inactiveStudents }}
              </h2>
            </div>

            <!-- DELETED -->
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
              <p class="text-sm font-semibold text-red-600">
                Dihapus
              </p>

              <h2 class="mt-2 text-4xl font-black text-red-700">
                {{ deletedStudents }}
              </h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TABLE -->
    <section class="rounded-3xl bg-white p-6 shadow-sm">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h2 class="text-xl font-bold text-slate-800">
            Daftar Akun Siswa
          </h2>

          <p class="mt-1 text-sm text-slate-500">
            Seluruh akun siswa yang sudah
            dibuat.
          </p>
        </div>

        <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600">
          {{ students.length }}
          Siswa
        </div>
      </div>

      <!-- TABLE -->
      <div class="overflow-x-auto">
        <table class="min-w-full overflow-hidden rounded-2xl">
          <thead class="bg-[#0B2B63] text-white">
            <tr>
              <th class="px-5 py-4 text-left text-sm font-semibold">
                No
              </th>

              <th class="px-5 py-4 text-left text-sm font-semibold">
                Nama
              </th>

              <th class="px-5 py-4 text-left text-sm font-semibold">
                Kelas
              </th>

              <th class="px-5 py-4 text-left text-sm font-semibold">
                Username
              </th>

              <th class="px-5 py-4 text-left text-sm font-semibold">
                Password Awal
              </th>

              <th class="px-5 py-4 text-left text-sm font-semibold">
                Status
              </th>

              <th class="px-5 py-4 text-center text-sm font-semibold">
                Aksi
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(
student, index
              ) in students" :key="student.id" class="border-b border-slate-100 transition hover:bg-slate-50">
              <!-- NO -->
              <td class="px-5 py-4 text-sm text-slate-700">
                {{ index + 1 }}
              </td>

              <!-- NAME -->
              <td class="px-5 py-4 font-semibold text-slate-800">
                {{ student.name }}
              </td>

              <!-- CLASS -->
              <td class="px-5 py-4 text-sm text-slate-600">
                {{ student.class }}
              </td>

              <!-- USERNAME -->
              <td class="px-5 py-4 text-sm text-slate-700">
                {{ student.username }}
              </td>

              <!-- PASSWORD -->
              <td class="px-5 py-4 text-sm text-slate-700">
                {{ student.password }}
              </td>

              <!-- STATUS -->
              <td class="px-5 py-4">
                <div v-if="student.active"
                  class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                  <UserCheck class="h-3 w-3" />

                  Aktif
                </div>

                <div v-else
                  class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                  <UserX class="h-3 w-3" />

                  Belum Login
                </div>
              </td>

              <!-- ACTION -->
              <td class="px-5 py-4 text-center">
                <button @click="
                  removeStudent(
                    student.id,
                  )
                  "
                  class="cursor-pointer inline-flex items-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">
                  <Trash2 class="h-4 w-4" />

                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>