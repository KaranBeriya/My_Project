<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

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

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


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
