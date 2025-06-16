<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,supplier,wholesaler,manufacturer'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $role = $user->role;
        $route = match ($role) {
            'admin' => '/admin/dashboard',
            'supplier' => '/supplier/dashboard',
            'wholesaler' => '/wholesaler/dashboard',
            'manufacturer' => '/manufacturer/dashboard',
            default => '/home',
        };

        return redirect($route);
    }
    public function createAdmin() {
    return view('auth.register-admin');
}

public function createSupplier() {
    return view('auth.register-supplier');
}

public function createManufacturer() {
    return view('auth.register-manufacturer');
}

public function createWholesaler() {
    return view('auth.register-wholesaler');
}

public function storeAdmin(Request $request) {
    return $this->storeWithRole($request, 'admin');
}
public function storeSupplier(Request $request) {
    return $this->storeWithRole($request, 'supplier');
}
public function storeManufacturer(Request $request) {
    return $this->storeWithRole($request, 'manufacturer');
}
public function storeWholesaler(Request $request) {
    return $this->storeWithRole($request, 'wholesaler');
}

// Reusable method
protected function storeWithRole(Request $request, $role) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $role,
    ]);

    auth()->login($user);

    return redirect('/dashboard'); // or redirect based on role
}

}

