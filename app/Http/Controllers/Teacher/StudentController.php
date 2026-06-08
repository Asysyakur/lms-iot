<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'name' => ['required'],

            'role' => [
                'required',
                Rule::in([
                    'student',
                    'teacher',
                ]),
            ],

            'class' => [
                Rule::requiredIf(
                    $request->role === 'student'
                ),
            ],

            'username' => [
                'required',
                'unique:users,username',
            ],

            'password' => [
                'required',
                'min:6',
            ],
        ]);

        User::create([

            'name' => $request->name,

            'class' =>
                $request->role === 'student'
                ? $request->class
                : null,

            'username' =>
                $request->username,

            'password' =>
                Hash::make(
                    $request->password
                ),

            'role' =>
                $request->role,
        ]);

        return back()->with(
            'success',
            'Akun berhasil dibuat'
        );
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with(
            'success',
            'Akun berhasil dihapus'
        );
    }

    public function update(
        Request $request,
        User $user
    ) {

        $request->validate([

            'name' => ['required'],

            'role' => [
                'required',
                Rule::in([
                    'student',
                    'teacher',
                ]),
            ],

            'class' => [
                Rule::requiredIf(
                    $request->role === 'student'
                ),
            ],

            'username' => [

                'required',

                Rule::unique(
                    'users',
                    'username'
                )->ignore($user->id),
            ],
        ]);

        $user->update([

            'name' =>
                $request->name,

            'class' =>
                $request->role === 'student'
                ? $request->class
                : null,

            'username' =>
                $request->username,

            'role' =>
                $request->role,
        ]);

        /**
         * PASSWORD OPTIONAL
         */
        if ($request->password) {

            $user->update([

                'password' =>
                    Hash::make(
                        $request->password
                    ),
            ]);
        }

        return back()->with(
            'success',
            'Akun berhasil diupdate'
        );
    }
}
