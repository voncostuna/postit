<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();

        // ✅ Only this admin's logs, 5 per page (pagination)
        $activities = ActivityLog::query()
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(5);

        return view('admin.profile.edit', compact('user', 'activities'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'logout',
                'model_type'  => get_class($user),
                'model_id'    => $user->id,
                'description' => 'Admin logged out',
                'ip_address'  => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 255),
                'created_at'  => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'delete',
                'model_type'  => get_class($user),
                'model_id'    => $user->id,
                'description' => 'Admin deleted their own account',
                'ip_address'  => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 255),
                'created_at'  => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('login')->with('success', 'Account deleted.');
    }
}
