<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        $user = Auth::user();
        
        // Get the user's role-specific data
        $roleData = null;
        switch ($user->role) {
            case 'supplier':
                $roleData = $user->supplier;
                break;
            case 'manufacturer':
                $roleData = $user->manufacturer;
                break;
            case 'wholesaler':
                $roleData = $user->wholesaler;
                break;
        }
        
        return view('profile.edit', [
            'user' => $user,
            'roleData' => $roleData,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update basic user information
        $user->name = $request->name;
        $user->email = $request->email;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        // Update role-specific data if provided
        if ($user->role === 'supplier' && $user->supplier) {
            $user->supplier->update([
                'business_address' => $request->address ?? $user->supplier->business_address,
                'phone' => $request->phone ?? $user->supplier->phone,
            ]);
        } elseif ($user->role === 'manufacturer' && $user->manufacturer) {
            $user->manufacturer->update([
                'business_address' => $request->address ?? $user->manufacturer->business_address,
                'phone' => $request->phone ?? $user->manufacturer->phone,
            ]);
        } elseif ($user->role === 'wholesaler' && $user->wholesaler) {
            $user->wholesaler->update([
                'business_address' => $request->address ?? $user->wholesaler->business_address,
                'phone' => $request->phone ?? $user->wholesaler->phone,
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    /**
     * Get user's profile picture URL.
     */
    public function getProfilePicture()
    {
        $user = Auth::user();
        
        if ($user->profile_picture) {
            return Storage::disk('public')->url($user->profile_picture);
        }
        
        // Return default avatar
        return asset('images/default-avatar.svg');
    }
}
