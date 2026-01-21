{{--
|--------------------------------------------------------------------------
| VIEW USER CONTENT
|--------------------------------------------------------------------------
| Displays a single content item created by the user.
|
| UI: Matches provided mock (white page, green side curves, author + date,
|     dark purple content card).
|
| Notes:
| - Like/Unlike buttons intentionally NOT included.
| - Includes image placeholder area if an image exists (or shows fallback box).
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
// Expecting: $article (passed from controller)
$authorName = optional($article->author)->name ?? (auth()->user()->name ?? 'You');
$dateText = optional($article->created_at)->format('F j, Y') ?? '';
$title = $article->title ?? '';
$content = $article->content ?? '';

// Adjust these depending on your schema/storage:
// If you store full URL in $article->image, it'll work as-is.
// If you store path like "uploads/xyz.jpg", use asset('storage/'.$article->image)
$imageUrl = null;
if (!empty($article->image)) {
$imageUrl = $article->image;
// If needed, switch to:
// $imageUrl = asset('storage/' . ltrim($article->image, '/'));
}
@endphp

<style>
    :root {
        --purple-3: #1a0648;
        --ink: #121212;
        --muted: #6d6d6d;
        --card: #ffffff;
        --line: #e9e9ee;
        --green: #0e8f01;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        height: 100%;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        background: #fff;
    }

    body {
        overflow-x: hidden;
    }

    .container,
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        max-width: 100% !important;
    }

    /* Page canvas */
    .show-wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 26px 28px 34px;
        overflow: hidden;
        background: #fff;
    }

    /* Green side curves (like mock) */
    .show-wrap::before {
        content: "";
        position: absolute;
        left: -260px;
        top: 190px;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: linear-gradient(180deg, #10b600 0%, #0a8c00 55%, #066300 100%);
        z-index: 0;
    }

    .show-wrap::after {
        content: "";
        position: absolute;
        right: -260px;
        top: 190px;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: linear-gradient(180deg, #10b600 0%, #0a8c00 55%, #066300 100%);
        z-index: 0;
    }

    .show-inner {
        position: relative;
        z-index: 1;
        width: min(980px, calc(100% - 120px));
        margin: 0 auto;
    }

    /* Top meta (author/date) */
    .meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
        margin: 6px 0 12px;
        padding-left: 2px;
    }

    .meta .author {
        font-size: 18px;
        font-weight: 800;
        color: #2a2a2a;
        line-height: 1.2;
    }

    .meta .date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #6a6a6a;
    }

    .meta .date svg {
        width: 16px;
        height: 16px;
        opacity: .85;
    }

    /* White outer card */
    .outer-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 10px;
        box-shadow: 0 12px 26px rgba(0, 0, 0, .10);
        padding: 14px 16px 18px;
    }

    /* Optional image area (top, inside outer card) */
    .image-area {
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

    .image-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .image-placeholder {
        font-size: 15px;
        font-weight: 700;
        color: #6a6a6a;
        opacity: .9;
        text-align: center;
        padding: 0 18px;
    }

    /* Dark purple content card */
    .content-card {
        background: var(--purple-3);
        border-radius: 8px;
        padding: 18px 18px 18px;
        color: #fff;
        box-shadow: 0 10px 22px rgba(0, 0, 0, .18);
    }

    .content-title {
        font-size: 16px;
        font-weight: 800;
        margin: 0 0 10px;
        color: #fff;
    }

    .content-body {
        font-size: 14px;
        font-weight: 500;
        line-height: 1.6;
        color: rgba(255, 255, 255, .92);
        white-space: pre-wrap;
        /* preserves newlines */
        margin: 0;
    }

    .back-row {
        margin: 0 0 10px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 34px;
        padding: 0 18px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .15);
        color: #2a2a2a;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        text-decoration: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, .10);
    }

    .back-btn:hover {
        transform: translateY(-1px);
    }

    .back-btn svg {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 900px) {
        .show-inner {
            width: calc(100% - 48px);
        }
    }

    @media (max-width: 560px) {
        .meta .author {
            font-size: 16px;
        }

        .image-area {
            height: 180px;
        }

        .content-body {
            font-size: 14px;
        }
    }
</style>

<div class="show-wrap">
    <div class="show-inner">

        <div class="back-row">
            <a href="{{ route('user.contents.index') }}" class="back-btn">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" stroke="#2a2a2a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back
            </a>
        </div>

        <div class="meta">
            <div class="author">{{ $authorName }}</div>
            <div class="date">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 3v2M17 3v2" stroke="#6a6a6a" stroke-width="2" stroke-linecap="round" />
                    <path d="M4 9h16" stroke="#6a6a6a" stroke-width="2" stroke-linecap="round" />
                    <path d="M6 5h12a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="#6a6a6a" stroke-width="2" />
                </svg>
                {{ $dateText }}
            </div>
        </div>

        <div class="outer-card">
            {{-- Image area: show uploaded image if present, otherwise placeholder box --}}
            <div class="image-area">
                @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="Post image">
                @else
                <div class="image-placeholder">No image uploaded for this post.</div>
                @endif
            </div>

            <div class="content-card">
                <div class="content-title">“{{ $title }}”</div>
                <p class="content-body">{{ $content }}</p>
            </div>
        </div>

    </div>
</div>
@endsection