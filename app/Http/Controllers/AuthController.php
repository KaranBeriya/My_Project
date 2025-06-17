<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use App\Notifications\NewUserRegistered;
use App\Notifications\RegistrationSuccessNotification;

class AuthController extends Controller
{
    // Show Register Page
    public function registerPage()
    {
        return view('auth.register');
    }

    // Show Login Page
    public function loginPage()
    {
        return view('auth.login');
    }

    // Handle Registration (AJAX-friendly with param-based errors)
    public function register(Request $request)
    {
        try {
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

            $data = $request->only('name', 'email', 'contact', 'password');
            $data['password'] = bcrypt($data['password']);

            if ($request->hasFile('profile_picture')) {
                $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
            }

            $user = User::create($data);

            // Notify all other users
            $otherUsers = User::where('id', '!=', $user->id)->get();
            Notification::send($otherUsers, new NewUserRegistered($user));

            // Notify registered user
            $user->notify(new RegistrationSuccessNotification($user));

            // Email verification
            event(new Registered($user));

            return response()->json([
                'success' => true,
                'message' => __('messages.registration_success'),
                'redirect_url' => route('login.page'),
            ]);
        } catch (\Exception $e) {
            \Log::error("Registration error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('messages.registration_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Handle Login (AJAX-friendly with param-based errors)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return response()->json([
                    'errors' => ['email' => [__('messages.email_not_verified')]],
                ], 403);
            }

            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => __('messages.login_success'),
                'redirect_url' => route('home'),
            ]);
        }

        return response()->json([
            'errors' => ['email' => [__('messages.invalid_credentials')]],
        ], 401);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard');
    }

    // Forgot Password Form
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    // Send Password Reset Link
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

    // Show Reset Password Form
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Handle New Password Submission
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
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.page')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // Language Switcher
    public function switch(Request $request)
    {
        $locale = $request->input('locale');

        if (!in_array($locale, ['en', 'es'])) {
            abort(400, 'Invalid locale');
        }

        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    }
}
