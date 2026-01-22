<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER MODULES
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // ✅ ACTIVITY LOG: REGISTER
        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'register',
            'model_type'  => User::class,
            'model_id'    => $user->id,
            'description' => 'Registered an account',
            'ip_address'  => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'created_at'  => now(),
        ]);

        // redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Admin account created successfully!');
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Account created successfully!');
    }

    // LOGIN MODULES
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt([
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->only('email', 'is_admin'));
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // status check
        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Your account is inactive. Please contact support.'])
                ->withInput($request->only('email', 'is_admin'));
        }

        // admin checkbox enforcement
        $wantsAdmin = $request->boolean('is_admin');
        if ($wantsAdmin && $user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This account is not an admin account.'])
                ->withInput($request->only('email', 'is_admin'));
        }

        // ✅ ACTIVITY LOG: LOGIN
        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'login',
            'model_type'  => User::class,
            'model_id'    => $user->id,
            'description' => $user->role === 'admin'
                ? 'Admin logged in'
                : 'User logged in',
            'ip_address'  => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'created_at'  => now(),
        ]);

        // redirect based on role
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!')
            : redirect()->route('user.dashboard')->with('success', 'Login successful!');
    }

    // LOGOUT MODULE
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // ✅ ACTIVITY LOG: LOGOUT
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'logout',
                'model_type'  => User::class,
                'model_id'    => $user->id,
                'description' => $user->role === 'admin'
                    ? 'Admin logged out'
                    : 'User logged out',
                'ip_address'  => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 255),
                'created_at'  => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }
}
