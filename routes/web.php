<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

// AUTH ROUTES (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

// LOGOUT (auth only)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// USER ROUTES (USER ONLY)
Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
});

// ADMIN ROUTES (ADMIN ONLY)
Route::prefix('admin')->middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});
