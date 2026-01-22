<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();

        // ✅ Only this user's logs, 5 per page (pagination)
        $activities = ActivityLog::query()
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(5);

        return view('user.profile.edit', compact('user', 'activities'));
    }

    public function update(Request $request)
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'model_type'  => \App\Models\User::class,
            'model_id'    => Auth::id(),
            'description' => 'Updated profile',
            'ip_address'  => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'created_at'  => now(),
        ]);

        return back()->with('success', 'Profile updated!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        DB::transaction(function () use ($user, $request) {
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'delete',
                'model_type'  => \App\Models\User::class,
                'model_id'    => $user->id,
                'description' => 'Deleted account',
                'ip_address'  => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 255),
                'created_at'  => now(),
            ]);

            $user->delete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }
}
