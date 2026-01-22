<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin can VIEW + SOFT DELETE only).
     *
     * Supports:
     * - q: search by name/email
     * - role: all|admin|user
     * - status: all|active|inactive
     * - trashed: 0|1  (0 = active users only, 1 = only deleted users)
     */
    public function index(Request $request)
    {
        $q       = trim((string) $request->get('q', ''));
        $role    = $request->get('role', 'all');        // all|admin|user
        $status  = $request->get('status', 'all');      // all|active|inactive
        $trashed = (string) $request->get('trashed', '0'); // 0|1

        $query = User::query()->orderByDesc('created_at');

        if ($trashed === '1') {
            $query->onlyTrashed();
        } else {
            // default = not deleted
            $query->whereNull('deleted_at');
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $users = $query
            ->paginate(10)
            ->appends($request->query());

        return view('admin.users.index', compact('users', 'q', 'role', 'status', 'trashed'));
    }

    /**
     * SOFT delete a user.
     *
     * Guardrails:
     * - Prevent deleting yourself
     * - Prevent deleting other admins (recommended)
     */
    public function destroy(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            return back()->with('info', 'That user is already deleted.');
        }

        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot delete an admin account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    // Not needed for VIEW + DELETE only.
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
}
