<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Support\SchoolClasses;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Class options students may self-register into.
     *
     * @var array<int, string>
     */
    /**
     * Validate and create a newly self-registered student account.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')],
            'class' => ['required', Rule::in(SchoolClasses::options())],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            'class' => $input['class'],
            'password' => $input['password'],
            'role' => 'student',
        ]);
    }
}
