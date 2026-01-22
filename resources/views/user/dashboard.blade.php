{{--
|-------------------------------------------------------------------------- 
| USER DASHBOARD 
|-------------------------------------------------------------------------- 
| Main landing page after user login.
|
| Update:
| - FEEDS redesigned to look like your content show UI (author + date + image + purple content card)
| - Removed like/unlike UI (not included)
|-------------------------------------------------------------------------- 
--}}
@extends('layouts.app')

@push('styles')
<style>
    :root {
        --postit-green: #0B7A0B;
        --postit-purple: #1E0F52;
        --postit-purple-2: #1a0648;
        --postit-orange-1: #ff8a3d;
        --postit-orange-2: #e05a1b;
        --bg-1: #ffffff;
        --bg-2: #fafafa;
        --bg-3: #f2f2f2;
        --card-shadow: 0 14px 34px rgba(0, 0, 0, .10);
        --soft-shadow: 0 10px 24px rgba(0, 0, 0, .10);
        --ink: #111;
    }

    body {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
    }

    main.container.py-4 {
        max-width: 100% !important;
        padding: 0 !important;
    }

    .dash-wrap {
        position: relative;
        min-height: calc(100vh - 90px);
        overflow: hidden;
        background:
            radial-gradient(900px 520px at 18% 0%, var(--bg-1) 0%, var(--bg-2) 55%, var(--bg-3) 100%),
            radial-gradient(700px 420px at 90% 85%, #ffffff 0%, #f6f6f6 60%, rgba(242, 242, 242, 0) 100%);
    }

    .dash-wrap::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            repeating-linear-gradient(0deg,
                rgba(0, 0, 0, .012) 0px,
                rgba(0, 0, 0, .012) 1px,
                rgba(0, 0, 0, 0) 2px,
                rgba(0, 0, 0, 0) 4px);
        mix-blend-mode: multiply;
        opacity: .35;
        z-index: 0;
    }

    .dash-accent {
        position: absolute;
        right: -140px;
        top: -120px;
        width: 520px;
        height: 520px;
        border-radius: 0 0 0 520px;
        background: radial-gradient(circle at 28% 28%,
                var(--postit-orange-1) 0%,
                #f36a21 40%,
                var(--postit-orange-2) 72%,
                #c94b12 100%);
        z-index: 1;
        pointer-events: none;
        filter: drop-shadow(0 18px 40px rgba(0, 0, 0, .10));
    }

    .dash-main {
        position: relative;
        z-index: 2;
        max-width: 1040px;
        margin: 0 auto;
        padding: 28px 22px 52px;
    }

    .welcome {
        margin-top: 8px;
    }

    .welcome h1 {
        font-size: 44px;
        font-weight: 900;
        margin: 0;
        line-height: 1.05;
        color: var(--ink);
        letter-spacing: -0.5px;
    }

    .welcome p {
        margin: 6px 0 0;
        color: #2f1b86;
        font-weight: 650;
    }

    .stat-row {
        margin-top: 22px;
        display: flex;
        gap: 26px;
        justify-content: center;
        align-items: stretch;
        flex-wrap: wrap;
    }

    .stat {
        width: 220px;
        height: 140px;
        color: #fff;
        border-radius: 14px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;

        background: linear-gradient(180deg, #0f8b0f 0%, var(--postit-green) 55%, #086408 100%);
        box-shadow: var(--soft-shadow);
        border: 1px solid rgba(255, 255, 255, .18);
        transform: translateZ(0);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .stat::after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 14px;
        background: radial-gradient(180px 120px at 30% 25%, rgba(255, 255, 255, .20), rgba(255, 255, 255, 0) 65%);
        pointer-events: none;
    }

    .stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, .14);
    }

    .stat .icon {
        position: absolute;
        left: 18px;
        top: 18px;
        opacity: .98;
        filter: drop-shadow(0 2px 0 rgba(0, 0, 0, .10));
    }

    .stat .num {
        font-size: 54px;
        font-weight: 900;
        line-height: 1;
        margin-top: 8px;
        text-shadow: 0 2px 0 rgba(0, 0, 0, .10);
    }

    .stat .label {
        margin-top: 12px;
        font-weight: 850;
        font-size: 12px;
        letter-spacing: .9px;
        text-transform: uppercase;
        opacity: .95;
    }

    /* ===== FEEDS (Show-like cards) ===== */
    .feeds-shell {
        margin-top: 26px;
        background: #fff;
        border-radius: 14px;
        padding: 18px 18px 24px;

        width: 112%;
        margin-left: -4%;
        margin-right: -8%;

        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 0, 0, .04);
    }

    .feeds-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: var(--postit-purple);
        font-weight: 900;
        margin-bottom: 12px;
    }

    .feeds-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feeds-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(30, 15, 82, .12), rgba(30, 15, 82, 0));
        margin: 10px 0 16px;
    }

    .feed-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* meta row (author + date) like show.blade */
    .feed-meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
        margin: 2px 2px 10px;
    }

    .feed-author {
        font-size: 18px;
        font-weight: 800;
        color: #2a2a2a;
        line-height: 1.2;
    }

    .feed-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #6a6a6a;
    }

    .feed-date svg {
        width: 16px;
        height: 16px;
        opacity: .85;
    }

    /* outer card */
    .feed-outer-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 10px;
        box-shadow: 0 12px 26px rgba(0, 0, 0, .10);
        padding: 14px 16px 18px;
    }

    /* image area */
    .feed-image-area {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, .10);
        background: #f3f3f7;
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .feed-image-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .feed-image-placeholder {
        font-size: 15px;
        font-weight: 700;
        color: #6a6a6a;
        opacity: .9;
        text-align: center;
        padding: 0 18px;
    }

    /* purple content card */
    .feed-content-card {
        background: var(--postit-purple-2);
        border-radius: 8px;
        padding: 18px;
        color: #fff;
        margin-top: 10px;
        box-shadow: 0 10px 22px rgba(0, 0, 0, .18);
    }

    .feed-title {
        font-weight: 900;
        font-size: 18px;
        margin: 0 0 10px;
        line-height: 1.25;
    }

    .feed-body {
        margin: 0;
        font-weight: 600;
        opacity: .95;
        line-height: 1.65;
        white-space: pre-line;
        /* keeps line breaks */
    }

    .empty {
        margin-top: 12px;
        padding: 16px;
        border-radius: 12px;
        background: rgba(30, 15, 82, .06);
        color: var(--postit-purple);
        font-weight: 750;
        border: 1px dashed rgba(30, 15, 82, .20);
    }

    @media (max-width: 900px) {
        .feeds-shell {
            width: 100%;
            margin: 26px 0 0;
        }

        .dash-accent {
            right: -180px;
            top: -150px;
        }
    }

    @media (max-width: 700px) {
        .dash-accent {
            display: none;
        }

        .stat {
            width: 100%;
            max-width: 380px;
        }

        .welcome h1 {
            font-size: 34px;
        }

        .feed-image-area {
            height: 180px;
        }
    }
</style>
@endpush

@section('content')
@php
use Illuminate\Support\Facades\Storage;

$user = auth()->user();

$drafts = $drafts ?? 0;
$totalPost = $totalPost ?? 0;
$published = $published ?? 0;

// Controller should pass $feeds as a collection of published Article models
$feeds = $feeds ?? collect();
@endphp

<div class="dash-wrap">
    <div class="dash-accent" aria-hidden="true"></div>

    <div class="dash-main">
        <div class="welcome">
            <h1>Welcome, {{ $user?->name ?? 'User' }}!</h1>
            <p>Your space to create, edit, and manage your posts starts here.</p>
        </div>

        <div class="stat-row">
            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-6Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M14 2v6h6" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M9 13h6M9 17h6" stroke="white" stroke-width="2" stroke-linecap="round" />
                        <path d="M8.2 10.2h4.2" stroke="white" stroke-width="2" stroke-linecap="round" />
                        <path d="M10.3 8.1v4.2" stroke="white" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="num">{{ $drafts }}</div>
                <div class="label">Drafts</div>
            </div>

            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M8 8h9M8 12h6" stroke="white" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="num">{{ $totalPost }}</div>
                <div class="label">Total Post</div>
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
        </div>

        {{-- FEEDS --}}
        <div class="feeds-shell">
            <div class="feeds-head">
                <div class="feeds-head-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 19h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M7 16V8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M12 16V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M17 16v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <span>Feeds</span>
                </div>
            </div>

            <div class="feeds-divider" aria-hidden="true"></div>

            <div class="feed-list">
                @forelse($feeds as $article)
                @php
                $authorName = optional($article->author)->name ?? 'User';
                $dateText = optional($article->created_at)->format('F j, Y') ?? '';
                $imagePath = $article->featured_image ?? null;
                $imageUrl = $imagePath ? Storage::url($imagePath) : null;

                // Optional: show a shorter preview (so feed doesn’t get too tall)
                $body = $article->content ?? '';
                $bodyPreview = \Illuminate\Support\Str::limit($body, 700);
                @endphp

                <div>
                    <div class="feed-meta">
                        <div class="feed-author">{{ $authorName }}</div>
                        <div class="feed-date">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 3v2M17 3v2" stroke="#6a6a6a" stroke-width="2" stroke-linecap="round" />
                                <path d="M4 9h16" stroke="#6a6a6a" stroke-width="2" stroke-linecap="round" />
                                <path d="M6 5h12a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="#6a6a6a" stroke-width="2" />
                            </svg>
                            {{ $dateText }}
                        </div>
                    </div>

                    <div class="feed-outer-card">
                        <div class="feed-image-area">
                            @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="Post image">
                            @else
                            <div class="feed-image-placeholder">No image uploaded for this post.</div>
                            @endif
                        </div>

                        <div class="feed-content-card">
                            <div class="feed-title">“{{ $article->title ?? 'Untitled' }}”</div>
                            <p class="feed-body">{{ $bodyPreview }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty">No feeds yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection