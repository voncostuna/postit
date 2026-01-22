{{--
|--------------------------------------------------------------------------
| ADMIN SHOW CONTENT
|--------------------------------------------------------------------------
| EXACTLY matches the USER show.blade UI (same layout/styles/patterns)
|
| Notes:
| - Expects $article (with author relation optional)
| - Uses featured_image (stored in public disk) like your user contents
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
$authorName = optional($article->author)->name ?? 'Unknown';
$dateText = optional($article->updated_at)->format('F j, Y')
?? optional($article->created_at)->format('F j, Y')
?? '';

$title = $article->title ?? '';
$content = $article->content ?? '';

// ✅ Featured image (matches your user setup)
$imageUrl = null;
if (!empty($article->featured_image)) {
$imageUrl = asset('storage/' . ltrim($article->featured_image, '/'));
}
@endphp

<style>
    :root {
        --purple-3: #1a0648;
        --ink: #121212;
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

    main.container.py-4 {
        max-width: 100% !important;
        padding: 0 !important;
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

    /* Green side curves */
    .show-wrap::before {
        content: "";
        position: absolute;
        left: -260px;
        top: 190px;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: linear-gradient(180deg, #20055f 0%, #15024b 100%);
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
        background: linear-gradient(145deg, #24055e 0%, #12002f 100%);
        z-index: 0;
    }

    .show-inner {
        position: relative;
        z-index: 1;
        width: min(980px, calc(100% - 120px));
        margin: 0 auto;
    }

    /* Back row */
    .back-row {
        margin-bottom: 10px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #2a2a2a;
        font-weight: 800;
        font-size: 14px;
    }

    .back-btn svg {
        width: 20px;
        height: 20px;
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

    /* Image area */
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
        margin-top: 10px;
        box-shadow: 0 10px 22px rgba(0, 0, 0, .18);
    }

    .content-title {
        font-size: 20px;
        font-weight: 900;
        margin: 0 0 10px;
        line-height: 1.25;
    }

    .content-body {
        font-size: 15px;
        font-weight: 600;
        line-height: 1.7;
        margin: 0;
        white-space: pre-wrap;
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
    }
</style>

<div class="show-wrap">
    <div class="show-inner">

        <div class="back-row">
            <a href="{{ route('admin.contents.index') }}" class="back-btn">
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