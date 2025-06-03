<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  // assuming you use default User model

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // 🔁 get all users
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'contact' => 'nullable|string|max:20', // optional, adjust as needed
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ],[
            'name.required' => 'Name is required',
        ]);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'contact' => $validated['contact'] ?? null,
            'profile_picture' => $profilePicturePath,
        ]);

        // Return JSON response with id and other details for JS
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'contact' => $user->contact,
            'profile_picture_url' => asset('storage/' . $user->profile_picture),
            ]
        ]);

    }

    // Show edit form
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

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

        
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }


}
