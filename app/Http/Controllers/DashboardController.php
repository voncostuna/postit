<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function user()
    {
        $userId = Auth::id();

        // Own articles only
        $totalPost = Article::where('author_id', $userId)->count();

        $drafts = Article::where('author_id', $userId)
            ->where('status', 'draft')
            ->count();

        $published = Article::where('author_id', $userId)
            ->where('status', 'published')
            ->count();

        // Status breakdown (optional)
        $statusBreakdown = Article::where('author_id', $userId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // ✅ REAL notifications: latest published posts by OTHER users
        $notifications = Article::with('author')
            ->where('status', 'published')
            ->where('author_id', '!=', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($article) {
                $name = $article->author->name ?? 'Someone';

                $initials = collect(explode(' ', trim($name)))
                    ->filter()
                    ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                    ->take(2)
                    ->implode('');

                return [
                    'initials' => $initials ?: 'NA',
                    'text' => $name . ' posted: "' . ($article->title ?? 'Untitled') . '"',
                ];
            });

        return view('user.dashboard', compact(
            'totalPost',
            'drafts',
            'published',
            'statusBreakdown',
            'notifications'
        ));
    }

    public function admin()
    {
        return view('admin.dashboard');
    }
}

