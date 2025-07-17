<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\userRegistered;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        Auth::logout();
        return view('auth.register');
    }

    /**
     * Display the admin registration view.
     */
    public function createOnline(): View
    {
        $serverMessage = '';
        $htmlCode = '';
        $url = 'http://localhost:8080';
    
        try {
            $response = Http::timeout(2)->get($url);
            if (!$response->successful()) {
                $serverMessage = "Server is down (HTTP {$response->status()})";
            }else{
                $htmlCode = $response->body();
                $htmlCode = str_replace('@csrf', csrf_field(), $htmlCode);
                $htmlCode = str_replace("{{ route('register.newUser') }}", route('register.newUser'), $htmlCode);
            }
        } catch (\Exception $e) {
            $serverMessage = "Server unavailable: {$e->getMessage()}";
        }

        return view('auth.online-register', compact('htmlCode', 'serverMessage'));
    }
    /**
     * Display the admin registration view.
     */
    public function createAdmin(): View
    {
        if(!Auth::check()){
            return view('welcome');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

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
     * Handle an new registration request.
     */
    public function newUser(Request $request): RedirectResponse
    {
                          
        // Validation starts
        $filePath = $request->file('license_document')->getPathname();
        $fileName = $request->file('license_document')->getClientOriginalName();

        $response = Http::timeout(60)->attach('file', fopen($filePath, 'r'), $fileName)->post('http://localhost:8080/application/validate');

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success') {
                //redirect with success
                $storePath =$request->file('license_document')->store('uploads', 'public');
                
                if($data['role'] === 'supplier'){
                    PendingUsers::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'role' => 'supplier',
                        'password' => Hash::make("password123"),
                        'business_address' => $data['business_address'],
                        'phone' => $data['phone'],
                        'license_document' => $fileName,
                        'document_path' => $storePath,
                        'materials_supplied' => $data['materials_supplied'],
                        'visitDate' => $data['visitDate'],
                    ]);
                }else{
                    PendingUsers::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'role' => 'wholesaler',
                        'password' => Hash::make("password123"),
                        'business_address' => $data['business_address'],
                        'phone' => $data['phone'],
                        'license_document' => $fileName,
                        'document_path' => $storePath,
                        'monthly_order_volume' => $data['monthly_order_volume'],
                        'visitDate' => $data['visitDate'],
                    ]);
                }
                

                $formattedDate = Carbon::parse($data['visitDate'])->format('l, F j, Y \a\t g:i A');
                Mail::to($data['email'])->queue(new userRegistered($data['name'], $formattedDate, 'supplier'));

                return redirect()->route('register.validation')->with([
                    'success' => $data['visitDate'], 
                    'name' => $data['name'],
                ]);

            } else {
                // Validation error
                $msg = $data['message'] ?? 'Unknown error';
                if (isset($data['details'])) {
                    $msg .= ': ' . $data['details'];
                }
                 
                return redirect()->route('register.validation')->with('error', $msg);
                
            }
        } else {
            // HTTP-level error
            return redirect()->route('register.validation')->with('error', 'Failed to contact validation server: ' . $response->status());
        }    
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
        // Notify all admins of new user registration
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\UserRegisteredNotification($user));

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
            'license_document' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'materials_supplied' => ['required', 'array'],
        ]);

        // Validation starts
        $filePath = $request->file('license_document')->getPathname();
        $fileName = $request->file('license_document')->getClientOriginalName();

        $response = Http::attach('file',fopen($filePath, 'r'),$fileName)->post('http://localhost:8080/application/validate');

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success') {
                //redirect with success
                $storePath =$request->file('license_document')->store('uploads', 'public');
                
                PendingUsers::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'supplier',
                    'business_address' => $request->business_address,
                    'phone' => $request->phone,
                    'license_document' => $fileName,
                    'document_path' => $storePath,
                    'materials_supplied' => json_encode($request->materials_supplied),
                    'visitDate' => $data['visitDate'],
                ]);

                return redirect()->route('register.validation')->with([
                    'success' => $data['visitDate'], 
                    'name' => $request->name,
                ]);

            } else {
                // Validation error
                $msg = $data['message'] ?? 'Unknown error';
                if (isset($data['details'])) {
                    $msg .= ': ' . $data['details'];
                }
                 
                return redirect()->route('register.validation')->with('error', $msg);
                
            }
        } else {
            // HTTP-level error
            return redirect()->route('register.validation')->with('error', 'Failed to contact valgidation server: ' . $response->status());
        }
        // Validation ends

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
            'license_document' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'production_capacity' => ['required', 'integer', 'min:1'],
            'specialization' => ['required', 'array'],
        ]);

        // Validation starts
        $filePath = $request->file('license_document')->getPathname();
        $fileName = $request->file('license_document')->getClientOriginalName();

        $response = Http::attach('file',fopen($filePath, 'r'),$fileName)->post('http://localhost:8080/application/validate');

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success') {
                //redirect with success
                $storePath =$request->file('license_document')->store('uploads', 'public');

                PendingUsers::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'manufacturer',
                    'business_address' => $request->business_address,
                    'phone' => $request->phone,
                    'license_document' => $fileName,
                    'document_path' => $storePath,
                    'production_capacity' =>  $request->production_capacity,                   
                    'specialization' => json_encode($request->specialization),
                    'visitDate' => $data['visitDate'],
                ]);

                return redirect()->route('register.validation')->with([
                    'success' => $data['visitDate'], 
                    'name' => $request->name,
                ]);

            } else {
                // Validation error
                $msg = $data['message'] ?? 'Unknown error';
                if (isset($data['details'])) {
                    $msg .= ': ' . $data['details'];
                }
                 
                return redirect()->route('register.validation')->with('error', $msg);
                
            }
        } else {
            // HTTP-level error
            return redirect()->route('register.validation')->with('error', 'Failed to contact valgidation server: ' . $response->status());
        }
        // Validation ends

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
            'license_document' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'business_type' => ['required', 'string'],
            'monthly_order_volume' => ['required', 'integer'],
            'preferred_categories' => ['required', 'array'],
        ]);

        // Validation starts
        $filePath = $request->file('license_document')->getPathname();
        $fileName = $request->file('license_document')->getClientOriginalName();

        $response = Http::attach('file',fopen($filePath, 'r'),$fileName)->post('http://localhost:8080/application/validate');

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success') {
                //redirect with success
                $storePath =$request->file('license_document')->store('uploads', 'public');

                PendingUsers::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'wholesaler',
                    'business_address' => $request->business_address,
                    'phone' => $request->phone,
                    'license_document' => $fileName,
                    'document_path' => $storePath,
                    'monthly_order_volume' => $request->monthly_order_volume,
                    'business_type' => $request->business_type,
                    'preferred_categories' => json_encode($request->preferred_categories),
                    'visitDate' => $data['visitDate'],
                ]);

                return redirect()->route('register.validation')->with([
                    'success' => $data['visitDate'], 
                    'name' => $request->name,
                ]);

            } else {
                // Validation error
                $msg = $data['message'] ?? 'Unknown error';
                if (isset($data['details'])) {
                    $msg .= ': ' . $data['details'];
                }
                 
                return redirect()->route('register.validation')->with('error', $msg);
                
            }
        } else {
            // HTTP-level error
            return redirect()->route('register.validation')->with('error', 'Failed to contact valgidation server: ' . $response->status());
        }
        // Validation ends

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

