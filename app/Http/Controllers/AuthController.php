<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        // redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Admin account created successfully!');
        }

        return redirect()->route('dashboard')
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->only('email', 'is_admin'));
        }

        // regenerate session after login
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

        // redirect based on role
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!')
            : redirect()->route('dashboard')->with('success', 'Login successful!');
    }


    // LOGOUT MODULE
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }
}
