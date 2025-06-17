<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Notifications\NewUserRegistered;
use App\Notifications\RegistrationSuccessNotification;

class AuthController extends Controller
{
    /* -------------------- Show Pages -------------------- */

    public function registerPage()
    {
        return view('auth.register');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /* -------------------- Registration -------------------- */

    public function register(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'contact' => 'nullable|string|max:20',
                'password' => 'required|string|min:6|confirmed',
                'profile_picture' => 'nullable|image|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Prepare data
            $data = $request->only('name', 'email', 'contact', 'password');
            $data['password'] = bcrypt($data['password']);

            if ($request->hasFile('profile_picture')) {
                $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
            }

            // Create user
            $user = User::create($data);

            // Notify other users
            $otherUsers = User::where('id', '!=', $user->id)->get();
            Notification::send($otherUsers, new NewUserRegistered($user));

            // Notify the registered user
            $user->notify(new RegistrationSuccessNotification($user));

            // Send email verification
            event(new Registered($user));

            return response()->json([
                'success' => true,
                'message' => 'Registered successfully. Please verify your email.',
            ]);
        } catch (\Exception $e) {
            \Log::error("Registration error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /* -------------------- Login -------------------- */

    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Email not verified
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your email address is not verified. Please check your inbox.',
                ])->withInput();
            }

            // Success
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    /* -------------------- Logout -------------------- */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }

    /* -------------------- Forgot/Reset Password -------------------- */

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.page')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /* -------------------- Language Switch -------------------- */

    public function switch(Request $request)
    {
        $locale = $request->input('locale');

        if (!in_array($locale, ['en', 'es'])) {
            abort(400);
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
