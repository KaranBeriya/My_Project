<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegistrationSuccessNotification;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        $source = Cache::has('all_users') ? 'Cache' : 'Database';
        $users = Cache::remember('all_users', now()->addHours(2), function () {
            return User::all();
        });


        return view('users.index', compact('users', 'source'));
    }

    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'contact' => 'nullable|string|max:20',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'password.required' => 'Password is required',
            ]);

            // File upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'contact' => $validated['contact'] ?? null,
                'profile_picture' => $profilePicturePath,
            ]);

            // Email verification
            event(new Registered($user));

            // Notify other users
            $otherUsers = User::where('id', '!=', $user->id)->get();
            Notification::send($otherUsers, new UserRegisteredNotification($user));

            // Notify new user
            $user->notify(new RegistrationSuccessNotification($user));

            // Clear old user list cache
            Cache::forget('all_users');

            return response()->json([
                'success' => true,
                'message' => 'User created successfully. Verification email sent.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->contact,
                    'profile_picture_url' => $profilePicturePath ? asset('storage/' . $user->profile_picture) : null,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('User store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user.',
            ], 500);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:user,admin',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($validated);

        // Clear cache after update
        Cache::forget('all_users');

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            // Clear cache after delete
            Cache::forget('all_users');

            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
