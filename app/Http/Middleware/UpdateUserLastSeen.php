<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateUserLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            auth()->user()->update([
                'last_seen_at' => now(),
            ]);
        }

        return $next($request);
    }
}