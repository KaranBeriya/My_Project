<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Mail\WelcomeUser;
use App\Notifications\RegistrationSuccessNotification;

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

Route::get('/forgot-password', [AuthController::class, 'requestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', fn() => view('auth.verify-email'))->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (!URL::hasValidSignature($request)) {
        abort(403, 'Invalid or expired verification link.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    Auth::login($user);
    return redirect('/dashboard')->with('message', 'Email verified successfully!');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Notifications Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.markAsRead');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAll');
});

/*
|--------------------------------------------------------------------------
| Language Switcher Routes
|--------------------------------------------------------------------------
*/
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::match(['get', 'post'], '/language/{locale?}', function ($locale = null) {
    if ($locale && in_array($locale, ['en', 'es'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (myapp/*)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('myapp')->group(function () {
    Route::view('/home', 'home')->name('home');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| Verified Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->get('/dashboard', fn() => view('dashboard'));

/*
|--------------------------------------------------------------------------
| Utility / Test / Cache Routes
|--------------------------------------------------------------------------
*/
// Preview welcome email
Route::get('/preview-email', function () {
    $user = User::latest()->first();
    return new WelcomeUser($user);
});

// Preview registration success notification mail
Route::get('/preview-registration-mail', function () {
    $user = User::first();
    return (new RegistrationSuccessNotification($user))->toMail($user);
});

// Test SMTP Email
Route::get('/test-mail', function () {
    Mail::raw('Email Verification.', function ($message) {
        $message->to('karanberiya9@gmail.com')->subject('Laravel Test Email');
    });
    return 'Mail sent!';
});

// Cache testing routes
Route::get('/test-cache', function () {
    $data = Cache::remember('test_data', 1, function () {
        return User::pluck('name');
    });

    return response()->json($data);
});

Route::get('/cache/users', function () {
    $users = Cache::remember('all_users', 60, function () {
        return User::all();
    });
    return response()->json($users);
});

Route::get('/cache/user/{id}', function ($id) {
    $user = Cache::remember("user_{$id}", 60, function () use ($id) {
        return User::findOrFail($id);
    });
    return response()->json($user);
});

Route::get('/cache/clear/users', function () {
    Cache::forget('all_users');
    return 'User cache cleared!';
});

/*
|--------------------------------------------------------------------------
| Redirection Shortcuts
|--------------------------------------------------------------------------
*/
Route::get('/users', fn() => redirect()->route('users.index'));
Route::get('/home', fn() => redirect()->route('home'));

Route::post('/language-switch', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'es'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

// Route::get('/users/datatable-view', function () {
//     return view('users.datatable');
// });