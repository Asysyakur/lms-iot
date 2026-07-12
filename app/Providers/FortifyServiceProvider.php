<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Fix: Bind LoginViewResponse contract to inertia response
        $this->app->singleton(LoginViewResponse::class, function () {
            return new class implements LoginViewResponse
            {
                public function toResponse($request)
                {
                    return inertia('auth/Login');
                }
            };
        });
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(
                strtolower((string) $request->input('email')).'|'.$request->ip()
            );
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id') ?? $request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | LOGIN VIEW
        |--------------------------------------------------------------------------
        */

        Fortify::loginView(function () {
            return inertia('auth/Login');
        });

        Fortify::registerView(function () {
            return inertia('auth/Register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        /*
        |--------------------------------------------------------------------------
        | CUSTOM LOGIN
        |--------------------------------------------------------------------------
        */

        Fortify::authenticateUsing(function (Request $request) {

            Log::info('LOGIN ATTEMPT', [
                'login' => $request->email,
            ]);

            $login = $request->email;

            $user = User::where('email', $login)
                ->orWhere('username', $login)
                ->first();

            if (! $user) {

                Log::error('USER NOT FOUND');

                return null;
            }

            if (! Hash::check(
                $request->password,
                $user->password
            )) {

                Log::error('PASSWORD WRONG');

                return null;
            }

            Log::info('LOGIN SUCCESS');

            return $user;
        });
    }
}
