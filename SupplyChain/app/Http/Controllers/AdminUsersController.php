<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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

        $records = DB::select('SELECT * FROM pending_users');
                
        return view('admin.usersmanagement.index', [
            'pendingUsers' => $records,
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
