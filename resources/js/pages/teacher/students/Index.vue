```vue
<!-- resources/js/pages/teacher/students/Index.vue -->

<script setup lang="ts">
import {
  ref,
  computed,
} from 'vue';

import {
  useForm,
  router,
} from '@inertiajs/vue3';

import TeacherSidebarLayout from '@/layouts/teacher/TeacherSidebarLayout.vue';

import {
  toast,
} from 'vue-sonner';

import {
  UserPlus,
  Trash2,
  Pencil,
} from 'lucide-vue-next';

defineOptions({
  layout:
    TeacherSidebarLayout,
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

  class:
    'X TKJ-T-1',

  username: '',

  password: '',
});

const deletedStudents =
  ref(0);

const editingStudent =
  ref<any>(null);

const editStudent =
  (
    student: Student
  ) => {

    editingStudent.value =
      student;

    form.name =
      student.name;

    form.class =
      student.class;

    form.username =
      student.username;

    form.password = '';
  };

const resetForm =
  () => {

    editingStudent.value =
      null;

    form.reset();

    form.class =
      'X TKJ-T-1';
  };

const totalStudents =
  computed(() => {

    return props.students.length;
  });

const activeStudents =
  computed(() => {

    return props.students.filter(
      (
        student: Student,
      ) =>
        student.active,
    ).length;
  });

const inactiveStudents =
  computed(() => {

    return props.students.filter(
      (
        student: Student,
      ) =>
        !student.active,
    ).length;
  });

const addStudent =
  () => {

    /**
     * UPDATE
     */
    if (
      editingStudent.value
    ) {

      form.put(
        `/teacher/students/${editingStudent.value.id}`,
        {
          preserveScroll:
            true,

          onSuccess:
            () => {

              toast.success(
                'Akun siswa berhasil diupdate',
              );

              resetForm();
            },

          onError:
            () => {

              toast.error(
                'Gagal update akun',
              );
            },
        },
      );

      return;
    }

    /**
     * CREATE
     */
    form.post(
      '/teacher/students',
      {
        preserveScroll:
          true,

        onSuccess:
          () => {

            toast.success(
              'Akun siswa berhasil dibuat',
            );

            resetForm();
          },

        onError:
          () => {

            toast.error(
              'Gagal membuat akun siswa',
            );
          },
      },
    );
  };

const removeStudent =
  async (
    id: number,
  ) => {

    const confirmed =
      confirm(
        'Hapus akun siswa ini?',
      );

    if (!confirmed) {
      return;
    }

    router.delete(
      `/teacher/students/${id}`,
      {
        preserveScroll:
          true,

        onSuccess:
          () => {

            deletedStudents.value++;

            toast.success(
              'Akun siswa berhasil dihapus',
            );
          },

        onError:
          () => {

            toast.error(
              'Gagal menghapus akun',
            );
          },
      },
    );
  };
</script>

<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-3xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-6 py-5 text-white shadow-lg">

      <div
        class="absolute right-0 top-0 h-44 w-44 rounded-full bg-white/10" />

      <div class="relative z-10">

        <div
          class="mb-3 inline-flex rounded-full bg-white/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider">
          Manajemen Siswa
        </div>

        <h1 class="text-3xl font-black">
          Kelola Akun Siswa
        </h1>

      </div>
    </section>

    <!-- TOP -->
    <section class="grid gap-5 xl:grid-cols-12">

      <!-- FORM -->
      <div
        class="rounded-3xl bg-white p-5 shadow-sm xl:col-span-7">

        <div class="mb-5 flex items-center gap-3">

          <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100">
            <UserPlus class="h-6 w-6 text-emerald-600" />
          </div>

          <div>
            <h2
              class="text-lg font-bold text-slate-800">
              {{
                editingStudent
                  ? 'Edit Akun Siswa'
                  : 'Tambah Akun Siswa'
              }}
            </h2>

            <p
              class="mt-1 text-sm text-slate-500">
              Isi data akun siswa.
            </p>
          </div>

        </div>

        <div
          class="grid gap-4 md:grid-cols-2">

          <!-- NAMA -->
          <div>
            <label
              class="mb-2 block text-sm font-semibold text-slate-700">
              Nama Siswa
            </label>

            <input
              v-model="form.name"
              type="text"
              placeholder="Contoh: Andi Pratama"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
          </div>

          <!-- KELAS -->
          <div>
            <label
              class="mb-2 block text-sm font-semibold text-slate-700">
              Kelas
            </label>

            <select
              v-model="form.class"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500">

              <!-- KELAS 10 -->
              <option>
                X TKJ-T-1
              </option>

              <option>
                X TKJ-T-2
              </option>

              <!-- KELAS 11 -->
              <option>
                XI TKJ-T-1
              </option>

              <option>
                XI TKJ-T-2
              </option>

              <!-- KELAS 12 -->
              <option>
                XII TKJ-T-1
              </option>

              <option>
                XII TKJ-T-2
              </option>

            </select>
          </div>

          <!-- USERNAME -->
          <div>
            <label
              class="mb-2 block text-sm font-semibold text-slate-700">
              Username
            </label>

            <input
              v-model="form.username"
              type="text"
              placeholder="contoh.username"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
          </div>

          <!-- PASSWORD -->
          <div>
            <label
              class="mb-2 block text-sm font-semibold text-slate-700">
              Password
            </label>

            <input
              v-model="form.password"
              type="text"
              placeholder="password123"
              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500" />
          </div>

        </div>

        <!-- BUTTON -->
        <div
          class="mt-5 flex justify-end">

          <button
            v-if="editingStudent"
            @click="resetForm"
            class="mr-3 rounded-2xl bg-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">
            Batal
          </button>

          <button
            @click="addStudent"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">

            <UserPlus class="h-4 w-4" />

            {{
              editingStudent
                ? 'Update Akun'
                : 'Simpan Akun'
            }}
          </button>

        </div>
      </div>

      <!-- STATS -->
      <div
        class="space-y-5 xl:col-span-5">

        <div
          class="rounded-3xl bg-white p-5 shadow-sm">

          <h2
            class="mb-5 text-base font-bold text-slate-800">
            Ringkasan Akun
          </h2>

          <div
            class="grid grid-cols-2 gap-4">

            <!-- TOTAL -->
            <div
              class="rounded-2xl border border-blue-200 bg-blue-50 p-4">

              <p
                class="text-xs font-semibold text-blue-600">
                Total Akun
              </p>

              <h2
                class="mt-2 text-3xl font-black text-blue-700">
                {{ totalStudents }}
              </h2>

            </div>

            <!-- ACTIVE -->
            <div
              class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

              <p
                class="text-xs font-semibold text-emerald-600">
                Aktif Login
              </p>

              <h2
                class="mt-2 text-3xl font-black text-emerald-700">
                {{ activeStudents }}
              </h2>

            </div>

            <!-- INACTIVE -->
            <div
              class="rounded-2xl border border-amber-200 bg-amber-50 p-4">

              <p
                class="text-xs font-semibold text-amber-600">
                Belum Login
              </p>

              <h2
                class="mt-2 text-3xl font-black text-amber-700">
                {{ inactiveStudents }}
              </h2>

            </div>

            <!-- DELETED -->
            <div
              class="rounded-2xl border border-red-200 bg-red-50 p-4">

              <p
                class="text-xs font-semibold text-red-600">
                Dihapus
              </p>

              <h2
                class="mt-2 text-3xl font-black text-red-700">
                {{ deletedStudents }}
              </h2>

            </div>

          </div>
        </div>
      </div>
    </section>

    <!-- TABLE -->
    <section
      class="rounded-3xl bg-white p-5 shadow-sm">

      <div
        class="mb-5 flex items-center justify-between">

        <div>
          <h2
            class="text-lg font-bold text-slate-800">
            Daftar Akun Siswa
          </h2>

          <p
            class="mt-1 text-sm text-slate-500">
            Seluruh akun siswa.
          </p>
        </div>

        <div
          class="rounded-2xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600">
          {{ students.length }}
          Siswa
        </div>

      </div>

      <!-- TABLE -->
      <div class="overflow-x-auto">

        <table
          class="min-w-full overflow-hidden rounded-2xl">

          <thead
            class="bg-[#0B2B63] text-white">

            <tr>

              <th
                class="px-4 py-3 text-left text-sm font-semibold">
                No
              </th>

              <th
                class="px-4 py-3 text-left text-sm font-semibold">
                Nama
              </th>

              <th
                class="px-4 py-3 text-left text-sm font-semibold">
                Kelas
              </th>

              <th
                class="px-4 py-3 text-left text-sm font-semibold">
                Username
              </th>

              <th
                class="px-4 py-3 text-center text-sm font-semibold">
                Aksi
              </th>

            </tr>
          </thead>

          <tbody>

            <tr
              v-for="(student, index) in students"
              :key="student.id"
              class="border-b border-slate-100 transition hover:bg-slate-50">

              <!-- NO -->
              <td
                class="px-4 py-4 text-sm text-slate-700">
                {{ index + 1 }}
              </td>

              <!-- NAME -->
              <td
                class="px-4 py-4 text-sm font-semibold text-slate-800">
                {{ student.name }}
              </td>

              <!-- CLASS -->
              <td
                class="px-4 py-4 text-sm text-slate-600">
                {{ student.class }}
              </td>

              <!-- USERNAME -->
              <td
                class="px-4 py-4 text-sm text-slate-700">
                {{ student.username }}
              </td>

              <!-- ACTION -->
              <td
                class="px-4 py-4 text-center">

                <div
                  class="flex items-center justify-center gap-2">

                  <!-- EDIT -->
                  <button
                    @click="editStudent(student)"
                    class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600">

                    <Pencil class="h-4 w-4" />

                    Edit
                  </button>

                  <!-- DELETE -->
                  <button
                    @click="removeStudent(student.id)"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-600">

                    <Trash2 class="h-4 w-4" />

                    Hapus
                  </button>

                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>
```
