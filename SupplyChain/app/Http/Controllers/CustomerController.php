<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    public function showRegistrationForm()
    {
        $customer = new Customer();
        return view('public.customer-register', [
            'ageGroups' => $customer->getAgeGroupOptions(),
            'incomeBrackets' => $customer->getIncomeBracketOptions(),
            'purchaseFrequencies' => $customer->getPurchaseFrequencyOptions(),
            'shoppingPreferences' => $customer->getShoppingPreferencesOptions(),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'age_group' => ['nullable', 'in:18-25,26-35,36-45,46-55,56-65,65+'],
            'gender' => ['nullable', 'in:male,female,other,prefer-not-to-say'],
            'income_bracket' => ['nullable', 'in:low,middle-low,middle,middle-high,high,prefer-not-to-say'],
            'shopping_preferences' => ['nullable', 'array'],
            'shopping_preferences.*' => ['string', 'in:casual_wear,formal_wear,sports_wear,luxury_items,budget_friendly,eco_friendly,latest_trends,classic_styles'],
            'purchase_frequency' => ['nullable', 'in:weekly,monthly,quarterly,yearly,occasional'],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'age_group' => $request->age_group,
            'gender' => $request->gender,
            'income_bracket' => $request->income_bracket,
            'shopping_preferences' => $request->shopping_preferences ?? [],
            'purchase_frequency' => $request->purchase_frequency,
            'is_active' => true,
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('cart.checkout')->with('success', 'Registration successful! Please complete your order.');
    }

    public function showLoginForm()
    {
        return view('public.customer-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $intendedUrl = $request->session()->get('url.intended', route('customer.dashboard'));
            
            return redirect()->to($intendedUrl)->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.home')->with('success', 'Logged out successfully!');
    }

    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get recent orders
        $recentOrders = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Get order statistics
        $totalOrders = $customer->customerOrders()->count();
        $totalSpent = $customer->customerOrders()
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        return view('public.customer-dashboard', [
            'customer' => $customer,
            'recentOrders' => $recentOrders,
            'totalOrders' => $totalOrders,
            'totalSpent' => $totalSpent,
        ]);
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        
        return view('public.customer-profile', [
            'customer' => $customer,
            'ageGroups' => $customer->getAgeGroupOptions(),
            'incomeBrackets' => $customer->getIncomeBracketOptions(),
            'purchaseFrequencies' => $customer->getPurchaseFrequencyOptions(),
            'shoppingPreferences' => $customer->getShoppingPreferencesOptions(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'age_group' => ['nullable', 'in:18-25,26-35,36-45,46-55,56-65,65+'],
            'gender' => ['nullable', 'in:male,female,other,prefer-not-to-say'],
            'income_bracket' => ['nullable', 'in:low,middle-low,middle,middle-high,high,prefer-not-to-say'],
            'shopping_preferences' => ['nullable', 'array'],
            'shopping_preferences.*' => ['string', 'in:casual_wear,formal_wear,sports_wear,luxury_items,budget_friendly,eco_friendly,latest_trends,classic_styles'],
            'purchase_frequency' => ['nullable', 'in:weekly,monthly,quarterly,yearly,occasional'],
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'age_group' => $request->age_group,
            'gender' => $request->gender,
            'income_bracket' => $request->income_bracket,
            'shopping_preferences' => $request->shopping_preferences ?? [],
            'purchase_frequency' => $request->purchase_frequency,
        ]);

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.customer-orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->findOrFail($id);

        return view('public.customer-order-detail', compact('order'));
    }
}