{{--
|-------------------------------------------------------------------------- 
| ADMIN DASHBOARD 
|-------------------------------------------------------------------------- 
| Updates requested:
| - Background MUST be pure white
| - Base font >= 16px
| - Stats MUST be in one row only (no wrapping)
|-------------------------------------------------------------------------- 
--}}
@extends('layouts.app')

@section('content')
@php
use App\Models\Article;
use App\Models\User;

$totalPosts = Article::count();
$drafts = Article::where('status', 'draft')->count();
$published = Article::where('status', 'published')->count();
$totalUsers = User::count();

$recent = Article::with('author')
->where('status', 'published')
->orderByDesc('created_at')
->limit(5)
->get();

$adminName = auth()->user()?->name ?? 'Admin';
@endphp

<style>
    :root {
        --postit-green: #0B7A0B;
        --postit-purple: #1E0F52;
        --postit-orange-1: #ff8a3d;
        --postit-orange-2: #e05a1b;
        --card-shadow: 0 14px 34px rgba(0, 0, 0, .10);
        --soft-shadow: 0 10px 24px rgba(0, 0, 0, .10);
        --ink: #111;
    }

    /* Force readable base font */
    html,
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 16px !important;
        background: #fff !important;
    }

    main.container.py-4 {
        max-width: 100% !important;
        padding: 0 !important;
        background: #fff !important;
    }

    /* WHITE background page */
    .admin-wrap {
        position: relative;
        min-height: calc(100vh - 90px);
        overflow: hidden;
        background: #fff;
        /* ✅ pure white */
    }

    /* Purple corner accent kept (like screenshot) */
    .admin-accent {
        position: absolute;
        right: -140px;
        top: -120px;
        width: 520px;
        height: 520px;
        border-radius: 0 0 0 520px;
        background: radial-gradient(circle at 28% 28%,
                #6a3bff 0%,
                #4c1fff 40%,
                #2f0fb8 72%,
                #1d0a6f 100%);
        z-index: 1;
        pointer-events: none;
        filter: drop-shadow(0 18px 40px rgba(0, 0, 0, .10));
    }

    .admin-main {
        position: relative;
        z-index: 2;
        max-width: 1100px;
        /* a bit wider so 4 cards fit in one row */
        margin: 0 auto;
        padding: 28px 22px 52px;
    }

    .welcome {
        margin-top: 8px;
    }

    .welcome h1 {
        font-size: 46px;
        font-weight: 900;
        margin: 0;
        line-height: 1.05;
        color: var(--ink);
        letter-spacing: -0.5px;
    }

    .welcome p {
        margin: 8px 0 0;
        color: #2f1b86;
        font-weight: 700;
        font-size: 16px;
        /* ✅ readable */
    }

    /* ✅ Stats in ONE ROW ONLY */
    .stat-row {
        margin-top: 18px;
        display: flex;
        gap: 18px;
        align-items: stretch;
        justify-content: space-between;
        flex-wrap: nowrap;
        /* ✅ no wrapping */
    }

    .stat {
        flex: 1 1 0;
        /* ✅ equal width */
        min-width: 0;
        /* prevents overflow issues */
        height: 120px;
        color: #fff;
        border-radius: 12px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
        padding: 16px 16px 14px;

        background: linear-gradient(180deg, #0f8b0f 0%, var(--postit-green) 55%, #086408 100%);
        box-shadow: var(--soft-shadow);
        border: 1px solid rgba(255, 255, 255, .18);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, .14);
    }

    .stat .icon {
        position: absolute;
        left: 14px;
        top: 14px;
        opacity: .98;
        filter: drop-shadow(0 2px 0 rgba(0, 0, 0, .10));
    }

    .stat .num {
        font-size: 50px;
        font-weight: 900;
        line-height: 1;
        margin-top: 6px;
        padding-left: 46px;
        text-shadow: 0 2px 0 rgba(0, 0, 0, .10);
    }

    .stat .label {
        margin-top: 10px;
        padding-left: 46px;
        font-weight: 900;
        font-size: 14px;
        /* ✅ readable */
        letter-spacing: .9px;
        text-transform: uppercase;
        opacity: .95;
    }

    /* Recent Activity panel */
    .activity-shell {
        margin-top: 22px;
        background: #fff;
        border-radius: 12px;
        padding: 18px 18px 20px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 0, 0, .06);
    }

    .activity-head {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 900;
        color: var(--postit-green);
        margin-bottom: 12px;
        font-size: 16px;
        /* ✅ readable */
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-top: 6px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #e05a1b;
        border-radius: 12px;
        padding: 12px 14px;
        color: #fff;
        font-weight: 800;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .08);
    }

    .activity-avatar {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        background: #fff;
        color: var(--postit-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        flex: 0 0 auto;
        text-transform: uppercase;
        font-size: 16px;
        /* ✅ readable */
    }

    .activity-text {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: 16px;
        /* ✅ readable */
        font-weight: 850;
    }

    .empty {
        padding: 14px;
        border-radius: 12px;
        background: rgba(30, 15, 82, .06);
        color: var(--postit-purple);
        font-weight: 800;
        border: 1px dashed rgba(30, 15, 82, .20);
        margin-top: 8px;
        font-size: 16px;
    }

    /* Keep ONE ROW on normal screens; only stack if truly tiny */
    @media (max-width: 900px) {
        .admin-main {
            max-width: 100%;
        }

        .stat-row {
            gap: 12px;
        }

        .stat .num {
            font-size: 44px;
        }
    }

    /* On very small screens, it’s impossible to keep 4 cards in one row without unreadable text,
       so we allow horizontal scrolling instead of wrapping. */
    @media (max-width: 720px) {
        .admin-accent {
            display: none;
        }

        .welcome h1 {
            font-size: 36px;
        }

        .stat-row {
            overflow-x: auto;
            padding-bottom: 6px;
        }

        .stat {
            flex: 0 0 240px;
            /* scrollable cards */
        }
    }
</style>

<div class="admin-wrap">
    <div class="admin-accent" aria-hidden="true"></div>

    <div class="admin-main">
        <div class="welcome">
            <h1>Welcome, {{ $adminName }}!</h1>
            <p>Turn your thoughts into posts that others can read and enjoy.</p>
        </div>

        <div class="stat-row">
            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6h16M7 6v14m10-14v14M9 10h6" stroke="white" stroke-width="2" stroke-linecap="round" />
                        <path d="M6 4h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" stroke="white" stroke-width="2" opacity=".35" />
                    </svg>
                </div>
                <div class="num">{{ $totalPosts }}</div>
                <div class="label">Total Posts</div>
            </div>

            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M8 8h9M8 12h6" stroke="white" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="num">{{ $drafts }}</div>
                <div class="label">Drafts</div>
            </div>

            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6 9 17l-5-5" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" stroke="white" stroke-width="2" opacity=".35" />
                    </svg>
                </div>
                <div class="num">{{ $published }}</div>
                <div class="label">Published</div>
            </div>

            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M16 11c1.657 0 3-1.79 3-4s-1.343-4-3-4-3 1.79-3 4 1.343 4 3 4Z" stroke="white" stroke-width="2" />
                        <path d="M8 11c1.657 0 3-1.79 3-4S9.657 3 8 3 5 4.79 5 7s1.343 4 3 4Z" stroke="white" stroke-width="2" opacity=".9" />
                        <path d="M2 21c0-3.314 2.686-6 6-6h0c3.314 0 6 2.686 6 6" stroke="white" stroke-width="2" stroke-linecap="round" />
                        <path d="M14 21c0-2.761 2.239-5 5-5h0c1.381 0 2.63.56 3.536 1.464" stroke="white" stroke-width="2" stroke-linecap="round" opacity=".9" />
                    </svg>
                </div>
                <div class="num">{{ $totalUsers }}</div>
                <div class="label">Total Users</div>
            </div>
        </div>

        <div class="activity-shell">
            <div class="activity-head">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2Z" fill="currentColor" />
                    <path d="M18 16v-5a6 6 0 1 0-12 0v5l-2 2h16l-2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                </svg>
                <span>Recent Activity</span>
            </div>

            <div class="activity-list">
                @forelse($recent as $a)
                @php
                $name = optional($a->author)->name ?? 'Someone';
                $initials = collect(preg_split('/\s+/', trim($name)))
                ->filter()
                ->map(fn($p) => strtoupper(mb_substr($p,0,1)))
                ->take(2)
                ->implode('') ?: 'U';
                $title = $a->title ?? 'Untitled';
                @endphp

                <div class="activity-item">
                    <div class="activity-avatar">{{ $initials }}</div>
                    <div class="activity-text">
                        {{ $name }} posted: “{{ $title }}”
                    </div>
                </div>
                @empty
                <div class="empty">No recent activity yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection