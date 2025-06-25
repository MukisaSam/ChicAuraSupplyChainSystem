<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    protected function redirectTo()
{
    $role = Auth::user()->role;

    return match ($role) {
        'admin' => '/admin/dashboard',
        'supplier' => '/supplier/dashboard',
        'wholesaler' => '/wholesaler/dashboard',
        'manufacturer' => '/manufacturer/dashboard',
        default => '/home',
    };
}

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,supplier,wholesaler,manufacturer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_verified'=> 'pending',
        ]);

        Auth::login($user);

        if ($user->is_verified === 'pending') {
    return redirect()->route('pending.validation');
}

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
