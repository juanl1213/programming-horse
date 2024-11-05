<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function index(Request $request)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $users = User::all();

        $editUserId = $request->query('editUserId');
        $editUser = null;
        if ($editUserId) {
            $editUser = User::find($editUserId);
        }

        // Pass the users and the editUser data to the view
        return view('admin.userstable', compact('users', 'editUser'));
    }

    public function adminupdate(Request $request, $id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->user_role !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Validate the request data
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_role' => 'required|string|in:User,Admin',
        ]);

        // Update the user in the database
        $user = User::findOrFail($id);
        $user->update([
            'user_name' => $request->user_name,
            'user_role' => $request->user_role,
        ]);

        return redirect()->route('userstable')->with('success', 'User updated successfully.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->dark_mode = $request->boolean('dark_mode');

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
