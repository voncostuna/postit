<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// USER controllers
use App\Http\Controllers\User\ContentController as UserContentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ActivityLogController as UserActivityLogController;

// ADMIN controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;

/*
|--------------------------------------------------------------------------
| PUBLIC / GUEST
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('landing'))->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (guests only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

/*
|--------------------------------------------------------------------------
| LOGOUT (auth only)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| USER ROUTES (USER ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.user'])->prefix('user')->as('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');

    // Contents (CRUD own)
    Route::resource('contents', UserContentController::class);

    // Activity Logs
    Route::get('/activity-logs', [UserActivityLogController::class, 'index'])->name('activity-logs.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::resource('users', AdminUserController::class);

    Route::resource('contents', AdminContentController::class);

    // Admin manages landing "Pages/Sections"
    Route::resource('pages', AdminPageController::class);

    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
});

/*
|--------------------------------------------------------------------------
| OPTIONAL: Redirect /dashboard based on role
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'user') {
        return redirect()->route('user.dashboard');
    }

    abort(403);
})->name('dashboard.redirect');