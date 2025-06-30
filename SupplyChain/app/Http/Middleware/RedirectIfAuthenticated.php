<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If trying to access login page while authenticated
                if ($request->routeIs('login')) {
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    return redirect()->route('login')->with('status', 'You have been logged out. Please log in with different credentials.');
                }
                
                // For other routes, redirect to appropriate dashboard
                $user = Auth::guard($guard)->user();
                $route = match ($user->role) {
                    'admin' => '/admin/dashboard',
                    'supplier' => '/supplier/dashboard',
                    'wholesaler' => '/wholesaler/dashboard',
                    'manufacturer' => '/manufacturer/dashboard',
                    default => '/dashboard',
                };
                return redirect($route);
            }
        }

        return $next($request);
    }
}
