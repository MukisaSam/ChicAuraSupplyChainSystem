<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $intendedUrl = $request->session()->get('url.intended', route('customer.dashboard'));
            
            return redirect()->to($intendedUrl)->with('success', 'Login successful!');
        }else{
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->role;

        $route = match ($role) {
            'admin' => '/admin/dashboard',
            'supplier' => '/supplier/dashboard',
            'wholesaler' => '/wholesaler/dashboard',
            'manufacturer' => '/manufacturer/dashboard',
            default => '/dashboard',
        };

        return redirect()->intended($route);
    }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
