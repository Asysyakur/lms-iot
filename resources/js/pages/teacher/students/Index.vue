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

// interface
interface Student {
  id: number;
  name: string;
  class: string | null;
  username: string;
  password: string;
  role: 'student' | 'teacher';
  last_seen_at: string | null;
}

const props = defineProps<{
  students: Student[];
}>();

const form = useForm({
  name: '',

  class: 'X TKJ-T-1',

  username: '',

  password: '',

  role: 'student',
});

const classOptions = [
  'X TKJ-T-1',
  'X TKJ-T-2',
  'XI TKJ-T-1',
  'XI TKJ-T-2',
  'XII TKJ-T-1',
  'XII TKJ-T-2',
];

const deletedStudents =
  ref(0);

const editingStudent =
  ref<any>(null);

const editStudent = (
  student: Student
) => {

  editingStudent.value =
    student;

  form.name =
    student.name;

  form.class =
    student.class ??
    'X TKJ-T-1';

  form.username =
    student.username;

  form.role =
    student.role;

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

const sortedStudents =
  computed(() => {

    return [
      ...props.students
    ].sort((a, b) => {

      /**
       * TEACHER DI ATAS
       */
      if (
        a.role === 'teacher'
        &&
        b.role !== 'teacher'
      ) {
        return -1;
      }

      if (
        a.role !== 'teacher'
        &&
        b.role === 'teacher'
      ) {
        return 1;
      }

      return 0;
    });
  });

const activeStudents =
  computed(() => {

    return props.students.filter(
      (student: any) => {

        if (!student.last_seen_at) {
          return false;
        }

        const lastSeen =
          new Date(
            student.last_seen_at
          ).getTime();

        const now =
          Date.now();

        /**
         * AKTIF JIKA
         * < 5 MENIT
         */
        return (
          now - lastSeen
          <
          5 * 60 * 1000
        );
      }
    ).length;
  });

const studentCount =
  computed(() => {

    return props.students.filter(
      (student) =>
        student.role === 'student'
    ).length;
  });

const inactiveStudents =
  computed(() => {

    return (
      props.students.length
      -
      activeStudents.value
    );
  });

const isOnline = (
  student: Student
) => {

  if (!student.last_seen_at) {
    return false;
  }

  const lastSeen =
    new Date(
      student.last_seen_at
    ).getTime();

  return (
    Date.now() - lastSeen
    <
    5 * 60 * 1000
  );
};

const formatLastSeen = (
  value: string | null
) => {

  if (!value) {
    return 'Belum pernah login';
  }

  const date =
    new Date(value);

  return date.toLocaleString(
    'id-ID',
    {
      dateStyle: 'medium',
      timeStyle: 'short',
    }
  );
};

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
                'akun berhasil diupdate',
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
              'akun berhasil dibuat',
            );

            resetForm();
          },

        onError:
          () => {

            toast.error(
              'Gagal membuat akun',
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
        'Hapus akun ini?',
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
              'akun berhasil dihapus',
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
  <div class="space-y-4">

    <!-- HEADER -->
    <section
      class="relative overflow-hidden rounded-xl bg-linear-to-r from-[#173B74] via-[#114084] to-emerald-500 px-5 py-4 text-white shadow-sm">

      <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10" />

      <div class="relative z-10">

        <div class="inline-flex rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">

          Manajemen Siswa
        </div>

        <h1 class="mt-3 text-2xl font-bold">

          Kelola Akun
        </h1>
      </div>
    </section>

    <!-- TOP -->
    <section class="grid gap-4 xl:grid-cols-12">

      <!-- FORM -->
      <div class="rounded-xl bg-white p-4 shadow-sm xl:col-span-7">

        <!-- HEADER -->
        <div class="mb-4 flex items-center gap-3">

          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">

            <UserPlus class="h-5 w-5 text-emerald-600" />
          </div>

          <div>

            <h2 class="text-sm font-bold text-slate-800">

              {{
                editingStudent
                  ? 'Edit Akun'
                  : 'Tambah Akun'
              }}
            </h2>

            <p class="mt-1 text-xs text-slate-500">

              Isi data akun.
            </p>
          </div>
        </div>

        <!-- FORM -->
        <div class="grid gap-4 md:grid-cols-2">

          <!-- NAMA -->
          <div>

            <label class="mb-2 block text-sm font-semibold text-slate-700">

              Nama Siswa
            </label>

            <input v-model="form.name" type="text" placeholder="Contoh: Andi Pratama"
              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />
          </div>

          <!-- KELAS -->
          <div v-if="form.role === 'student'">

            <label class="mb-2 block text-sm font-semibold text-slate-700">

              Kelas
            </label>

            <select v-model="form.class"
              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">
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

            <input v-model="form.username" type="text" placeholder="contoh.username"
              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />
          </div>

          <!-- PASSWORD -->
          <div>

            <label class="mb-2 block text-sm font-semibold text-slate-700">

              Password
            </label>

            <input v-model="form.password" type="text" placeholder="password123"
              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500" />
          </div>
          <!-- ROLE -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
              Role
            </label>

            <select v-model="form.role"
              class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">
              <option value="student">
                Student
              </option>

              <option value="teacher">
                Teacher
              </option>
            </select>
          </div>
        </div>

        <!-- BUTTON -->
        <div class="mt-4 flex justify-end">

          <button v-if="editingStudent" @click="resetForm"
            class="mr-3 rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">

            Batal
          </button>

          <button @click="addStudent" :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">

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
      <div class="space-y-4 xl:col-span-5">

        <div class="rounded-xl bg-white p-4 shadow-sm">

          <h2 class="mb-4 text-sm font-bold text-slate-800">

            Ringkasan Akun
          </h2>

          <div class="grid grid-cols-2 gap-3">

            <!-- TOTAL -->
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-3">

              <p class="text-[11px] font-semibold text-blue-600">

                Total Akun
              </p>

              <h2 class="mt-2 text-2xl font-black text-blue-700">

                {{ totalStudents }}
              </h2>
            </div>

            <!-- ACTIVE -->
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">

              <p class="text-[11px] font-semibold text-emerald-600">

                Aktif Login
              </p>

              <h2 class="mt-2 text-2xl font-black text-emerald-700">

                {{ activeStudents }}
              </h2>
            </div>

            <!-- INACTIVE -->
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">

              <p class="text-[11px] font-semibold text-amber-600">

                Belum Login
              </p>

              <h2 class="mt-2 text-2xl font-black text-amber-700">

                {{ inactiveStudents }}
              </h2>
            </div>

            <!-- DELETED -->
            <div class="rounded-xl border border-red-200 bg-red-50 p-3">

              <p class="text-[11px] font-semibold text-red-600">

                Dihapus
              </p>

              <h2 class="mt-2 text-2xl font-black text-red-700">

                {{ deletedStudents }}
              </h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TABLE -->
    <section class="rounded-xl bg-white p-4 shadow-sm">

      <!-- HEADER -->
      <div class="mb-4 flex items-center justify-between">

        <div>

          <h2 class="text-sm font-bold text-slate-800">

            Daftar Akun
          </h2>

          <p class="mt-1 text-xs text-slate-500">

            Seluruh Akun.
          </p>
        </div>

        <div class="rounded-xl bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-600">

          {{ studentCount }}
          Siswa
        </div>
      </div>

      <!-- TABLE -->
      <div class="overflow-x-auto rounded-xl border border-slate-200">

        <table class="min-w-full">

          <!-- HEAD -->
          <thead class="bg-[#0B2B63] text-white">

            <tr>

              <th class="px-4 py-3 text-left text-[11px] font-semibold">

                No
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold">

                Nama
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold"> Role </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold">

                Kelas
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold">

                Username
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold">
                Status
              </th>

              <th class="px-4 py-3 text-left text-[11px] font-semibold">
                Last Seen
              </th>

              <th class="px-4 py-3 text-center text-[11px] font-semibold">

                Aksi
              </th>
            </tr>
          </thead>

          <!-- BODY -->
          <tbody>

            <tr v-for="(
              student,
                index
              ) in sortedStudents" :key="student.id" class="border-b border-slate-100 transition hover:bg-slate-50">

              <!-- NO -->
              <td class="px-4 py-3 text-sm text-slate-700">

                {{ index + 1 }}
              </td>

              <!-- NAME -->
              <td class="px-4 py-3 text-sm font-semibold text-slate-800">

                {{ student.name }}
              </td>

              <!-- ROLE -->
              <td class="px-4 py-3 text-sm"> <span v-if="student.role === 'teacher'"
                  class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"> Teacher </span>
                <span v-else class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700"> Student
                </span>
              </td>

              <!-- CLASS -->
              <td class="px-4 py-3 text-sm text-slate-600">

                {{ student.class }}
              </td>

              <!-- USERNAME -->
              <td class="px-4 py-3 text-sm text-slate-700">

                {{ student.username }}
              </td>

              <!-- STATUS -->
              <td class="px-4 py-3">

                <span v-if="isOnline(student)"
                  class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                  <span class="h-2 w-2 rounded-full bg-emerald-500" />

                  Online
                </span>

                <span v-else
                  class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                  <span class="h-2 w-2 rounded-full bg-slate-400" />

                  Offline
                </span>
              </td>

              <!-- LAST SEEN -->
              <td class="px-4 py-3 text-sm text-slate-600">

                {{ formatLastSeen(student.last_seen_at) }}
              </td>

              <!-- ACTION -->
              <td class="px-4 py-3 text-center">

                <div class="flex items-center justify-center gap-2">

                  <!-- EDIT -->
                  <button @click="
                    editStudent(
                      student
                    )
                    "
                    class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-2 text-[11px] font-semibold text-white transition hover:bg-amber-600">

                    <Pencil class="h-3.5 w-3.5" />

                    Edit
                  </button>

                  <!-- DELETE -->
                  <button @click="
                    removeStudent(
                      student.id
                    )
                    "
                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 px-3 py-2 text-[11px] font-semibold text-white transition hover:bg-red-600">

                    <Trash2 class="h-3.5 w-3.5" />

                    Hapus
                  </button>
                </div>
              </td>
            </tr>

            <!-- EMPTY -->
            <tr v-if="!students.length">

              <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">

                Belum ada akun
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>
```
