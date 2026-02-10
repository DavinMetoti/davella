<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the profile settings page.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'date_of_birth' => 'nullable|date|before:today',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address', 'bio', 'date_of_birth']));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists('profile-photos/' . $user->profile_photo)) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
        }

        // Store new photo
        $fileName = time() . '_' . $user->id . '.' . $request->file('profile_photo')->getClientOriginalExtension();
        $path = $request->file('profile_photo')->storeAs('profile-photos', $fileName, 'public');

        $user->update(['profile_photo' => $fileName]);

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Remove the user's profile photo.
     */
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo && Storage::disk('public')->exists('profile-photos/' . $user->profile_photo)) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
        }

        $user->update(['profile_photo' => null]);

        return redirect()->back()->with('success', 'Profile photo removed successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
