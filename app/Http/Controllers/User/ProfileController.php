<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // ✅ IMPORTANT: your blade file is resources/views/user/profile/edit.blade.php
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // (Add update logic later)
        return back()->with('success', 'Profile updated!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        DB::transaction(function () use ($user) {
            // If you have related tables that don't cascade, delete them first here.
            // Example:
            // \App\Models\Article::where('author_id', $user->id)->delete();

            $user->delete();
        });

        // ✅ logout + invalidate session AFTER delete
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }
}
