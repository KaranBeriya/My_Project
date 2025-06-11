<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Mail\WelcomeUser;


/*
|--------------------------------------------------------------------------
| Public Routes (Guest)
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('dashboard'))->name('dashboard');

Route::get('/login', [AuthController::class, 'loginPage'])->name('login.page');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');

Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('forgot-password', [AuthController::class, 'requestForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::get('/preview-email', function () {
    $user = User::latest()->first();
    return new WelcomeUser($user);
});

// Show verify email prompt (only if logged in)
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')
    ->name('verification.notice');

// ✅ VERIFY LINK HANDLER — Allows verification even if not logged in
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id);

    if (! $user) {
        abort(404, 'User not found.');
    }

    if (! URL::hasValidSignature($request)) {
        abort(403, 'Invalid or expired verification link.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    Auth::login($user); // Optional: auto-login after verification

    return redirect('/dashboard')->with('message', 'Email verified successfully!');
})->name('verification.verify');

// Resend verify link (only if logged in)
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (with Prefix)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('myapp')->group(function () {
    Route::view('/home', 'home')->name('home');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| Verified-Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->get('/dashboard', fn() => view('dashboard'));

/*
|--------------------------------------------------------------------------
| Misc Routes
|--------------------------------------------------------------------------
*/

// Shortcut redirects
Route::get('/users', fn() => redirect()->route('users.index'));
Route::get('/home', fn() => redirect()->route('home'));

// Test email route (Gmail SMTP)
Route::get('/test-mail', function () {
    Mail::raw('Email Verification.', function ($message) {
        $message->to('rajakhed@gmail.com')->subject('Laravel Test Email');
    });

    return 'Mail sent!';
});

// Optional manual login for testing
// Route::get('/force-login', function () {
//     $user = User::find(21);
//     Auth::login($user);
//     return '✅ Logged in as user ID: ' . $user->id;
// });
