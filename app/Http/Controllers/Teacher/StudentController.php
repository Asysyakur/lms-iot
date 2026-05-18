<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'class' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'password' => ['required', 'min:6'],
        ]);

        User::create([
            'name' => $request->name,
            'class' => $request->class,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        return back()->with(
            'success',
            'Akun siswa berhasil dibuat'
        );
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with(
            'success',
            'Akun siswa berhasil dihapus'
        );
    }
}