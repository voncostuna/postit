{{-- 
|-------------------------------------------------------------------------- 
| USER DASHBOARD 
|-------------------------------------------------------------------------- 
| Main landing page after user login.
|-------------------------------------------------------------------------- 
--}}
@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root{
        --postit-green:#0B7A0B;
        --postit-purple:#1E0F52;
        --postit-orange-1:#ff8a3d;
        --postit-orange-2:#e05a1b;
        --bg-1:#ffffff;
        --bg-2:#fafafa;
        --bg-3:#f2f2f2;
        --card-shadow: 0 14px 34px rgba(0,0,0,.10);
        --soft-shadow: 0 10px 24px rgba(0,0,0,.10);
    }

    body{
        font-family:"Albert Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    main.container.py-4{
        max-width: 100% !important;
        padding: 0 !important;
    }

    .dash-wrap{
        position: relative;
        min-height: calc(100vh - 90px);
        overflow: hidden;

        background:
            radial-gradient(900px 520px at 18% 0%, var(--bg-1) 0%, var(--bg-2) 55%, var(--bg-3) 100%),
            radial-gradient(700px 420px at 90% 85%, #ffffff 0%, #f6f6f6 60%, rgba(242,242,242,0) 100%);
    }

    .dash-wrap::before{
        content:"";
        position:absolute;
        inset:0;
        pointer-events:none;
        background:
            repeating-linear-gradient(0deg,
                rgba(0,0,0,.012) 0px,
                rgba(0,0,0,.012) 1px,
                rgba(0,0,0,0) 2px,
                rgba(0,0,0,0) 4px
            );
        mix-blend-mode:multiply;
        opacity:.35;
        z-index:0;
    }

    .dash-accent{
        position:absolute;
        right:-140px;
        top:-120px;
        width:520px;
        height:520px;
        border-radius: 0 0 0 520px;
        background: radial-gradient(
            circle at 28% 28%,
            var(--postit-orange-1) 0%,
            #f36a21 40%,
            var(--postit-orange-2) 72%,
            #c94b12 100%
        );
        z-index:1;
        pointer-events:none;
        filter: drop-shadow(0 18px 40px rgba(0,0,0,.10));
    }

    .dash-main{
        position: relative;
        z-index: 2;
        max-width: 1000px;
        margin: 0 auto;
        padding: 28px 22px 52px;
    }

    .welcome{ margin-top: 8px; }
    .welcome h1{
        font-size: 44px;
        font-weight: 900;
        margin: 0;
        line-height: 1.05;
        color:#111;
        letter-spacing: -0.5px;
    }
    .welcome p{
        margin: 6px 0 0;
        color: #2f1b86;
        font-weight: 650;
    }

    .stat-row{
        margin-top: 22px;
        display:flex;
        gap: 26px;
        justify-content: center;
        align-items: stretch;
        flex-wrap: wrap;
    }

    .stat{
        width: 220px;
        height: 140px;
        color:#fff;
        border-radius: 14px;
        position: relative;
        display:flex;
        flex-direction:column;
        justify-content:center;
        text-align:center;

        background: linear-gradient(180deg, #0f8b0f 0%, var(--postit-green) 55%, #086408 100%);
        box-shadow: var(--soft-shadow);
        border: 1px solid rgba(255,255,255,.18);
        transform: translateZ(0);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .stat::after{
        content:"";
        position:absolute;
        inset:0;
        border-radius: 14px;
        background: radial-gradient(180px 120px at 30% 25%, rgba(255,255,255,.20), rgba(255,255,255,0) 65%);
        pointer-events:none;
    }

    .stat:hover{
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(0,0,0,.14);
    }

    .stat .icon{
        position:absolute;
        left:18px;
        top:18px;
        opacity:.98;
        filter: drop-shadow(0 2px 0 rgba(0,0,0,.10));
    }
    .stat .num{
        font-size: 54px;
        font-weight: 900;
        line-height: 1;
        margin-top: 8px;
        text-shadow: 0 2px 0 rgba(0,0,0,.10);
    }
    .stat .label{
        margin-top: 12px;
        font-weight: 850;
        font-size: 12px;
        letter-spacing: .9px;
        text-transform: uppercase;
        opacity:.95;
    }

    /* Feeds panel */
    .notif-shell{
        margin-top: 26px;
        background:#fff;
        border-radius: 14px;
        padding: 18px 18px 24px;
        min-height: 320px;

        width: 112%;
        margin-left: -4%;
        margin-right: -8%;

        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,.04);
    }

    .notif-head{
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap:10px;
        color: var(--postit-purple);
        font-weight: 900;
        margin-bottom: 12px;
    }

    .notif-head-left{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .notif-divider{
        height:1px;
        background: linear-gradient(90deg, rgba(30,15,82,.12), rgba(30,15,82,0));
        margin: 10px 0 14px;
    }

    .notif-item{
        background: linear-gradient(180deg, #241064 0%, var(--postit-purple) 100%);
        color:#fff;
        border-radius: 12px;
        padding: 12px 14px;
        display:flex;
        align-items:center;
        gap:12px;
        margin-top: 14px;

        box-shadow: 0 10px 18px rgba(0,0,0,.08);
        border: 1px solid rgba(255,255,255,.08);
    }

    .avatar{
        width:38px;height:38px;
        border-radius:999px;
        background:#fff;
        color: var(--postit-purple);
        font-weight: 900;
        display:flex;
        align-items:center;
        justify-content:center;
        flex: 0 0 auto;
        box-shadow: 0 6px 12px rgba(0,0,0,.10);
        text-transform: uppercase;
    }

    .notif-text{
        font-weight: 650;
        overflow:hidden;
    }

    .feed-title{
        font-weight: 850;
        white-space: nowrap;
        overflow:hidden;
        text-overflow: ellipsis;
    }

    .feed-meta{
        display:block;
        opacity:.85;
        font-weight: 650;
        margin-top: 2px;
        font-size: 12px;
        white-space: nowrap;
        overflow:hidden;
        text-overflow: ellipsis;
    }

    .empty{
        margin-top: 12px;
        padding: 16px;
        border-radius: 12px;
        background: rgba(30,15,82,.06);
        color: var(--postit-purple);
        font-weight: 750;
        border: 1px dashed rgba(30,15,82,.20);
    }

    @media (max-width: 900px){
        .notif-shell{
            width: 100%;
            margin: 26px 0 0;
        }
        .dash-accent{
            right:-180px;
            top:-150px;
        }
    }
    @media (max-width: 700px){
        .dash-accent{ display:none; }
        .stat{ width: 100%; max-width: 380px; }
        .welcome h1{ font-size: 34px; }
        .feed-title, .feed-meta{ white-space: normal; }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();

    // DO NOT default to fake numbers; assume controller provides these.
    $drafts    = $drafts    ?? 0;
    $totalPost = $totalPost ?? 0;
    $published = $published ?? 0;

    // Feeds must come from controller (empty is fine, but no fake items)
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
                        <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-6Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M14 2v6h6" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 13h6M9 17h6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8.2 10.2h4.2" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.3 8.1v4.2" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="num">{{ $drafts }}</div>
                <div class="label">Drafts</div>
            </div>

            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M8 8h9M8 12h6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="num">{{ $totalPost }}</div>
                <div class="label">Total Post</div>
            </div>

            <div class="stat">
                <div class="icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6 9 17l-5-5" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" stroke="white" stroke-width="2" opacity=".35"/>
                    </svg>
                </div>
                <div class="num">{{ $published }}</div>
                <div class="label">Published</div>
            </div>
        </div>

        {{-- FEEDS --}}
        <div class="notif-shell">
            <div class="notif-head">
                <div class="notif-head-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 19h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 16V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 16V5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M17 16v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Feeds</span>
                </div>
            </div>

            <div class="notif-divider" aria-hidden="true"></div>

            @forelse($feeds as $feed)
                @php
                    // supports either array or model
                    $authorName = data_get($feed, 'author.name')
                        ?? data_get($feed, 'author_name')
                        ?? data_get($feed, 'author')
                        ?? 'User';

                    $initials = collect(preg_split('/\s+/', trim($authorName)))
                        ->filter()
                        ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))
                        ->take(2)
                        ->implode('') ?: 'U';

                    $title = data_get($feed, 'title') ?? data_get($feed, 'text') ?? 'New activity';
                    $time  = data_get($feed, 'created_at')
                        ? \Illuminate\Support\Carbon::parse(data_get($feed, 'created_at'))->diffForHumans()
                        : null;

                    $meta = $time ? ($authorName . ' • ' . $time) : $authorName;
                @endphp

                <div class="notif-item">
                    <div class="avatar">{{ $initials }}</div>
                    <div class="notif-text">
                        <div class="feed-title">{{ $title }}</div>
                        <small class="feed-meta">{{ $meta }}</small>
                    </div>
                </div>
            @empty
                <div class="empty">
                    No feeds yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
