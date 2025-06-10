<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // ✅ Mail class include kiya
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Public Routes (Only for guests)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard'); 
})->name('dashboard');

Route::get('/login', [AuthController::class, 'loginPage'])->name('login.page');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');

Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('forgot-password', [AuthController::class, 'requestForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    Auth::loginUsingId($request->route('id')); // ✅ User ko manually login kar rahe hain (testing ke liye)

    //dd("✅ Verify route chal gaya", $request->user()); // 👈 Debug karo yahaan

    $request->fulfill(); // ⚠️ Ye line temporarily comment bhi kar sakte ho testing me
    return redirect('/dashboard')->with('message', 'Email verified successfully!');
})->middleware(['signed'])->name('verification.verify'); // ❌ 'auth' ha

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| User Management Routes (prefix: myapp) with auth middleware
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

// Optional redirect shortcuts
Route::get('/users', fn() => redirect()->route('users.index'));
Route::get('/home', fn() => redirect()->route('home'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| ✅ Test Mail Route (Gmail SMTP check)
|--------------------------------------------------------------------------
*/
Route::get('/test-mail', function () {
    Mail::raw('Email Verification.', function ($message) {
        $message->to('rajakhed@gmail.com') // ✅ Yahan apna Gmail ID likho
                ->subject('Laravel Test Email');
    });

    return 'Mail sent!';
});

Route::get('/force-login', function () {
    $user = User::find(21); // 👈 Yahan apna user ID daalein
    Auth::login($user);
    return '✅ Manually logged in as user ID: ' . $user->id;
});
