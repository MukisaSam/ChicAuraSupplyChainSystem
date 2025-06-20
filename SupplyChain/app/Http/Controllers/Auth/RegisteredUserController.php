<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Display the admin registration view.
     */
    public function createAdmin(): View
    {
        return view('auth.register-admin');
    }

    /**
     * Display the supplier registration view.
     */
    public function createSupplier(): View
    {
        return view('auth.register-supplier');
    }

    /**
     * Display the manufacturer registration view.
     */
    public function createManufacturer(): View
    {
        return view('auth.register-manufacturer');
    }

    /**
     * Display the wholesaler registration view.
     */
    public function createWholesaler(): View
    {
        return view('auth.register-wholesaler');
    }

    /**
     * Handle an admin registration request.
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
            'role' => ['required', 'in:admin,supplier,manufacturer,wholesaler'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));
        
        return redirect('/admin/users');
    }

    /**
     * Handle a supplier registration request.
     */
    public function storeSupplier(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'license_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'materials_supplied' => ['required', 'array'],
        ]);

        $licensePath = $request->file('license_document')->store('licenses', 'public');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'supplier',
        ]);

        Supplier::create([
            'user_id' => $user->id,
            'business_address' => $request->business_address,
            'phone' => $request->phone,
            'license_document' => $licensePath,
            'materials_supplied' => json_encode($request->materials_supplied),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/supplier/dashboard');
    }

    /**
     * Handle a manufacturer registration request.
     */
    public function storeManufacturer(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'license_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'production_capacity' => ['required', 'integer', 'min:1'],
            'specialization' => ['required', 'array'],
        ]);

        $licensePath = $request->file('license_document')->store('licenses', 'public');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'manufacturer',
        ]);

        Manufacturer::create([
            'user_id' => $user->id,
            'business_address' => $request->business_address,
            'phone' => $request->phone,
            'license_document' => $licensePath,
            'production_capacity' => $request->production_capacity,
            'specialization' => json_encode($request->specialization),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/manufacturer/dashboard');
    }

    /**
     * Handle a wholesaler registration request.
     */
    public function storeWholesaler(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'license_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'business_type' => ['required', 'string'],
            'preferred_categories' => ['required', 'array'],
            'monthly_order_volume' => ['required', 'integer', 'min:1'],
        ]);

        $licensePath = $request->file('license_document')->store('licenses', 'public');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'wholesaler',
        ]);

        Wholesaler::create([
            'user_id' => $user->id,
            'business_address' => $request->business_address,
            'phone' => $request->phone,
            'license_document' => $licensePath,
            'business_type' => $request->business_type,
            'preferred_categories' => json_encode($request->preferred_categories),
            'monthly_order_volume' => $request->monthly_order_volume,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/wholesaler/dashboard');
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

