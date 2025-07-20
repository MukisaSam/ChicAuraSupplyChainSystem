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
        Auth::logout();
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        //First checks if user is a customer
        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Audit log for customer login
            if (Auth::guard('customer')->check()) {
                \App\Models\AuditLog::create([
                    'user_id' => Auth::guard('customer')->id(),
                    'action' => 'login',
                    'details' => 'Customer logged in from IP: ' . $request->ip(),
                ]);
            }

            $intendedUrl = $request->session()->get('url.intended', route('customer.dashboard'));
            
            return redirect()->to($intendedUrl)->with('success', 'Login successful!');
        }else{
            //checks if user is a vendor
            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->role;

            // Audit log for user login
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'details' => 'User logged in from IP: ' . $request->ip(),
            ]);

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
        $user = Auth::user();
        if ($user) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'details' => 'User logged out from IP: ' . $request->ip(),
            ]);
        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
