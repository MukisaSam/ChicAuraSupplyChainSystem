<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Supplier,Manufacturer,Wholesaler};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class AdminUsersController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return redirect() ->route('welcome');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $pendingUsers = DB::select('SELECT * FROM pending_users');
        $activeUsers = DB::select('SELECT * FROM users');
        $numberofUsers = DB::table('users')->count();
        $admin = DB::table('users')->where('role', 'admin')->count();
        $manufacturer = DB::table('users')->where('role', 'manufacturer')->count();
        $supplier = DB::table('users')->where('role', 'supplier')->count();
        $wholesaler = DB::table('users')->where('role', 'wholesaler')->count();

                
        return view('admin.usersmanagement.index', [
            'pendingUsers' => $pendingUsers,
            'activeUsers' => $activeUsers,
            'numberofUsers' => $numberofUsers,
            'admin' => $admin,
            'manufacturer' => $manufacturer,
            'supplier' => $supplier,
            'wholesaler' => $wholesaler,
            
        ]);
    }

    public function addUserView(Request $request)
    {
        $id = $request->id;

        // Fetch the user record
        $record = DB::table('pending_users')->where('id', $id)->first();

        //First pass
        $raw1 = json_decode($record->preferred_categories, true);
        $raw2 = json_decode($record->specialization, true);
        $raw3 = json_decode($record->materials_supplied, true);
        
        //Second pass
        $preferred_categories = json_decode($raw1, true);
        $specialization = json_decode($raw2, true);
        $materials_supplied = json_decode($raw3, true);
        
        // Pass the record to the view
        return view('admin.usersmanagement.adduser', [
            'record' => $record,
            'preferred_categories' => $preferred_categories,
            'specialization' => $specialization,
            'materials_supplied' => $materials_supplied,
        ]);
    }

    public function editUserView(Request $request)
    {
        $id = $request->id;

        // Fetch the user record
        $data = DB::select('SELECT `role` FROM users WHERE id = ?', [$id]);

        $role = $data[0]->role ?? '';

        if($role == 'admin'){
           $record = DB::table('users')->where('id', $id)->first();
                   
            // Pass the record to the view
            return view('admin.usersmanagement.edituser', [
                'record' => $record,
            ]);
        }
        else if ($role == 'supplier') {
            $record = DB::table('users as u')->join('suppliers as s', 'u.id', '=', 's.user_id')
            ->select(
                'u.id', 'u.name', 'u.email', 'u.profile_picture', 'u.password', 'u.role',
                's.business_address', 's.phone', 's.license_document', 's.materials_supplied'
            )->where('u.id', $id)->first();

            //First pass
            $raw3 = json_decode($record->materials_supplied, true);
        
            //Second pass
            $materials_supplied = json_decode($raw3, true);
        
            // Pass the record to the view
            return view('admin.usersmanagement.edituser', [
                'record' => $record,
                'materials_supplied' => $materials_supplied,
            ]);

        }else if($role == 'manufacturer'){
            $record = DB::table('users as u')->join('manufacturers as m', 'u.id', '=', 'm.user_id')
            ->select(
                'u.id', 'u.name', 'u.email', 'u.profile_picture', 'u.password', 'u.role',
                'm.business_address', 'm.phone', 'm.license_document', 'm.production_capacity', 'm.specialization'
            )->where('u.id', $id)->first();

            //First pass
            $raw2 = json_decode($record->specialization, true);
        
            //Second pass
            $specialization = json_decode($raw2, true);
        
            // Pass the record to the view
            return view('admin.usersmanagement.edituser', [
                'record' => $record,
                'specialization' => $specialization,
            ]);
        }else{
            $record = DB::table('users as u')->join('wholesalers as w', 'u.id', '=', 'w.user_id')
            ->select(
                'u.id', 'u.name', 'u.email', 'u.profile_picture', 'u.password', 'u.role',
                'w.business_address', 'w.phone', 'w.license_document', 'w.business_type' , 'w.preferred_categories', 'w.monthly_order_volume'
            )->where('u.id', $id)->first();

            //First pass
            $raw1 = json_decode($record->preferred_categories, true);
        
            //Second pass
            $preferred_categories = json_decode($raw1, true);
        
            // Pass the record to the view
            return view('admin.usersmanagement.edituser', [
                'record' => $record,
                'preferred_categories' => $preferred_categories,
            ]);
        }

    }

    public function addUser(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:supplier,manufacturer,wholesaler'],
            'business_address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'profile_picture' => ['nullable', 'image'],
            'materials_supplied' => ['array'],
            'business_type' => ['string'],
            'monthly_order_volume' => ['numeric'],
            'preferred_categories' => ['array'],
            'production_capacity' => ['numeric'],
            'specialization' => ['array'],
        ]);

        //Store image and create path
        $storePath = null;
        if ($request->hasFile('profile_picture')) {
            $storePath = $request->file('profile_picture')->store('uploads', 'public');
        }

        //Retrive Some Details From pending_users
        $id = $request->id;
        $details = DB::table('pending_users')->where('id', $id)->first();

        //Check password

        if($request->password == null){
            $password = $details->password;
        }else{
            $request->validate(['password' => ['required', 'confirmed', Rules\Password::defaults()],]);
            $password = Hash::make($request->password);
        }

        $details->document_path;

        //Add to Users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
            'profile_picture' => $storePath,
        ]);
        // Audit log for admin user creation
        if (Auth::check()) {
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin_create_user',
                'details' => 'Admin created user: ' . $user->email . ' (role: ' . $user->role . ')',
            ]);
        }

        $role = $request->role;
        if ($role == 'supplier') {
            Supplier::create([
                'user_id' => $user->id,
                'business_address' => $request->business_address,
                'phone' => $request->phone,
                'license_document' => $details->license_document,
                'materials_supplied' => json_encode($request->materials_supplied),
            ]);
        }else if ($role == 'wholesaler') {
            Wholesaler::create([
                'user_id' => $user->id,
                'business_address' => $request->business_address,
                'phone' => $request->phone,
                'license_document' => $details->license_document,
                'business_type' => $request->business_type,
                'monthly_order_volume' => $request->monthly_order_volume,
                'preferred_categories' => json_encode($request->preferred_categories),
            ]);
        } else if ($role == 'manufacturer') {
            Manufacturer::create([
                'user_id' => $user->id,
                'business_address' => $request->business_address,
                'phone' => $request->phone,
                'license_document' => $details->license_document,
                'production_capacity' => $request->production_capacity,
                'specialization' => json_encode($request->specialization),
            ]);
        }

        //Remove record from pending_users
        DB::delete('DELETE FROM pending_users WHERE id = ?', [$id]);
        
        
        return redirect()->route('admin.users');
        
    }

    public function removeUser(Request $request){
        $id = $request->id;
        $user = DB::table('users')->where('id', $id)->first();
        if($request->database == 'users'){
            //Remove record from users
            DB::delete('DELETE FROM users WHERE id = ?', [$id]);
            // Audit log for admin user deletion
            if (Auth::check()) {
                \App\Models\AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'admin_delete_user',
                    'details' => 'Admin deleted user: ' . ($user->email ?? 'ID ' . $id),
                ]);
            }
            return redirect()->route('admin.users');
        }else{
            //Remove record from pending_users
            DB::delete('DELETE FROM pending_users WHERE id = ?', [$id]);
            return redirect()->route('admin.users');
        }
    }

    public function getUsers(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('role', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Get paginated results
        $users = $query->paginate(10);

        // Get stats for the dashboard
        $stats = [
            'total_users' => User::count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'supplier_count' => User::where('role', 'supplier')->count(),
            'manufacturer_count' => User::where('role', 'manufacturer')->count(),
            'wholesaler_count' => User::where('role', 'wholesaler')->count(),
        ];

        return response()->json([
            'users' => $users,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,supplier,manufacturer,wholesaler',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,supplier,manufacturer,wholesaler',
            'status' => 'required|in:active,inactive,suspended,pending'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function ajaxIndex(Request $request)
    {
        try {
            $users = User::query();

            if ($request->has('search')) {
                $users->where(function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            $users = $users->get();
            
            return view('admin.users._table', compact('users'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load users table',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ajaxShow(User $user)
    {
        return view('admin.users._show', compact('user'));
    }

    public function ajaxStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function ajaxUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'is_active' => 'boolean',
        ]);
        $user->update($data);
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function ajaxDestroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
}
