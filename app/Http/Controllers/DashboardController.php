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

        // FEEDS: ALL published posts by ALL users (including the logged-in user)
        $feeds = Article::with('author')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('user.dashboard', compact(
            'totalPost',
            'drafts',
            'published',
            'statusBreakdown',
            'feeds'
        ));
    }


    public function admin()
    {
        return view('admin.dashboard');
    }
}
