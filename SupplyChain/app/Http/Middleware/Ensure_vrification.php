<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsVerified
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_verified) {
            return redirect()->route('pending.validation');
        }
        return $next($request);
    }
}
