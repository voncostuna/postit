{{-- 
|-------------------------------------------------------------------------- 
| USER PROFILE
|-------------------------------------------------------------------------- 
| Profile page UI (real data only)
|-------------------------------------------------------------------------- 
--}}
@extends('layouts.app')

@push('styles')
<style>
    :root{
        --postit-green:#0B7A0B;
        --postit-purple:#1E0F52;
        --postit-orange:#E05A1B;
    }

    main.container.py-4{
        max-width: 100% !important;
        padding: 0 !important;
    }

    .profile-wrap{
        position: relative;
        min-height: calc(100vh - 90px);
        overflow: hidden;
        background: radial-gradient(1200px 700px at 25% 0%, #ffffff 0%, #fbfbfb 45%, #f4f4f4 100%);
        padding: 26px 22px 48px;
    }

    .profile-accent{
        position:absolute;
        right:-140px;
        top:-120px;
        width:520px;
        height:520px;
        border-radius: 0 0 0 520px;
        background: radial-gradient(circle at 28% 28%, #ff8a3d 0%, #f36a21 40%, #e05a1b 72%, #c94b12 100%);
        z-index: 0;
        pointer-events:none;
    }

    .profile-main{
        position: relative;
        z-index: 2;
        max-width: 1040px;
        margin: 0 auto;
    }

    .top-row{
        display: grid;
        grid-template-columns: 1fr 220px;
        gap: 22px;
    }

    .profile-card{
        background:#fff;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0,0,0,.12);
        padding: 22px;
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 22px;
    }

    .avatar-big{
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: var(--postit-green);
    }

    .profile-info h1{
        font-size: 42px;
        font-weight: 900;
        color: var(--postit-green);
        margin: 0;
    }

    .profile-handle{
        font-weight: 800;
        opacity: .85;
        margin-bottom: 12px;
    }

    .actions{
        padding-top: 58px;
        display:flex;
        flex-direction: column;
        gap: 10px;
        align-items: flex-end;
    }

    .action-btn{
        width: 180px;
        height: 34px;
        border-radius: 999px;
        border: none;
        font-weight: 900;
        cursor: pointer;
    }

    .btn-logout{ background: var(--postit-green); color:#fff; }
    .btn-delete{ background: var(--postit-purple); color:#fff; }

    .activity-shell{
        margin-top: 22px;
        background:#fff;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0,0,0,.12);
        padding: 18px;
    }

    .activity-head{
        font-weight: 900;
        color: var(--postit-purple);
        margin-bottom: 14px;
    }

    .act-item{
        background: var(--postit-purple);
        color:#fff;
        border-radius: 12px;
        padding: 12px 14px;
        display:flex;
        gap: 12px;
        margin-top: 12px;
    }

    .act-avatar{
        width:38px;
        height:38px;
        border-radius:999px;
        background:#fff;
        color: var(--postit-purple);
        font-weight: 900;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .empty-state{
        padding: 24px;
        text-align: center;
        font-weight: 700;
        color:#888;
    }

    /* ✅ Custom pagination (so UI won't look broken) */
    .pagination-wrap{
        margin-top: 14px;
        display:flex;
        justify-content:center;
    }

    .paginate-bar{
        display:flex;
        align-items:center;
        gap: 10px;
        background:#fff;
        border: 1px solid #eee;
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
        border-radius: 999px;
        padding: 10px 12px;
    }

    .paginate-btn{
        height: 34px;
        padding: 0 14px;
        border-radius: 999px;
        border: none;
        font-weight: 900;
        cursor: pointer;
        background: var(--postit-purple);
        color: #fff;
        text-decoration: none;
        display:inline-flex;
        align-items:center;
        justify-content:center;
    }

    .paginate-btn.disabled{
        opacity: .45;
        pointer-events: none;
    }

    .paginate-info{
        font-weight: 900;
        color: var(--postit-purple);
        font-size: 13px;
        padding: 0 6px;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
@php
    $displayName = $user->name;
    $handle = '@' . strtolower(str_replace(' ', '', $displayName));
    $initials = collect(explode(' ', $displayName))
        ->map(fn($p) => strtoupper(mb_substr($p,0,1)))
        ->take(2)
        ->implode('');
@endphp

<div class="profile-wrap">
    <div class="profile-accent"></div>

    <div class="profile-main">
        <div class="top-row">
            <div class="profile-card">
                <div class="avatar-big"></div>

                <div class="profile-info">
                    <h1>{{ $displayName }}</h1>
                    <div class="profile-handle">{{ $handle }}</div>
                    <div class="profile-meta">
                        Member since {{ $user->created_at->format('F j, Y') }}
                    </div>
                </div>
            </div>

            <div class="actions">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="action-btn btn-logout">LOGOUT</button>
                </form>

                <form method="POST" action="{{ route('user.profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button class="action-btn btn-delete">DELETE ACCOUNT</button>
                </form>
            </div>
        </div>

        <div class="activity-shell">
            <div class="activity-head">RECENT ACTIVITIES</div>

            @if(isset($activities) && $activities->count())
                @foreach($activities as $activity)
                    <div class="act-item">
                        <div class="act-avatar">{{ $initials }}</div>

                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <div>{{ $activity->description }}</div>

                            <div style="font-size:12px; opacity:.85; font-weight:700;">
                                {{ optional($activity->created_at)->format('M j, Y g:i A') }}
                                @if(!empty($activity->action))
                                    • {{ strtoupper($activity->action) }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- ✅ Replace default Laravel links() with custom UI --}}
                @if($activities->lastPage() > 1)
                    <div class="pagination-wrap">
                        <div class="paginate-bar">
                            {{-- Prev --}}
                            @if($activities->onFirstPage())
                                <span class="paginate-btn disabled">PREV</span>
                            @else
                                <a class="paginate-btn" href="{{ $activities->previousPageUrl() }}">PREV</a>
                            @endif

                            {{-- Info --}}
                            <div class="paginate-info">
                                Page {{ $activities->currentPage() }} of {{ $activities->lastPage() }}
                            </div>

                            {{-- Next --}}
                            @if($activities->hasMorePages())
                                <a class="paginate-btn" href="{{ $activities->nextPageUrl() }}">NEXT</a>
                            @else
                                <span class="paginate-btn disabled">NEXT</span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    No activity yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
