{{--
|--------------------------------------------------------------------------
| USER CONTENT LIST
|--------------------------------------------------------------------------
| Displays all contents created by the logged-in user.
|
| UI: Matches the provided mock (purple left curve + white right, pill filters,
|     dark purple table shell, simple 3 vertical dots)
|
| Updates requested:
| - Search input + filter buttons BIGGER
| - Table wider (landscape)
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
$status = request('status', 'all'); // all|draft|published
$q = request('q', '');
@endphp

<style>
    :root {
        --purple-1: #5b2bff;
        --purple-2: #2b0a6a;
        --purple-3: #1a0648;
        --ink: #121212;
        --muted: #6d6d6d;
        --orange: #e85f1a;
        --green: #0e8f01;
        --card: #ffffff;
        --line: #e9e9ee;
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

    .main,
    #app,
    .app,
    .content,
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
    }

    /* Page canvas */
    .contents-wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 24px 32px 32px;
        overflow: hidden;
        background: #fff;
    }

    /* Top-left purple wedge */
    .contents-wrap::before {
        content: "";
        position: absolute;
        left: -120px;
        top: -140px;
        width: 520px;
        height: 360px;
        background: linear-gradient(145deg, var(--purple-1) 0%, #3b0db6 55%, var(--purple-2) 100%);
        border-radius: 320px;
        transform: rotate(-18deg);
        z-index: 0;
    }

    /* Bottom-left purple wedge */
    .contents-wrap::after {
        content: "";
        position: absolute;
        left: -260px;
        bottom: -260px;
        width: 520px;
        height: 520px;
        background: linear-gradient(145deg, #24055e 0%, #12002f 100%);
        border-radius: 50%;
        z-index: 0;
    }

    .contents-inner {
        position: relative;
        z-index: 1;
        min-height: calc(100vh - 32px);
        display: flex;
        flex-direction: column;
    }

    /* Header */
    .top-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 6px 6px 0;
    }

    .new-post-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 22px;
        border-radius: 9px;
        background: var(--orange);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        letter-spacing: .2px;
        text-decoration: none;
        box-shadow: 0 10px 20px rgba(0, 0, 0, .18);
        text-transform: uppercase;
        margin-top: 6px;

        white-space: nowrap;
        line-height: 1;
        min-width: 110px;
    }

    .title-block {
        width: 100%;
        text-align: center;
        margin-top: 2px;
    }

    .title-block h1 {
        margin: 0;
        font-size: 40px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.08;
    }

    .title-block p {
        margin: 6px 0 0;
        font-size: 16px;
        font-weight: 700;
        color: #3b0db6;
        opacity: .95;
    }

    /* Search + filters bar (BIGGER + WIDER) */
    .search-row {
        margin: 18px auto 22px;
        width: min(980px, calc(100% - 140px));
        /* wider, but still centered */
        background: #fff;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .14);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        border: 1px solid rgba(0, 0, 0, .10);
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        height: 54px;
        /* bigger */
        padding: 0 14px;
        /* bigger */
        background: #fff;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .14);
    }

    .search-icon {
        width: 22px;
        /* bigger */
        height: 22px;
        /* bigger */
        opacity: .85;
        flex: 0 0 auto;
    }

    .search-input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 16px;
        /* bigger */
        font-weight: 600;
        color: var(--ink);
        background: transparent;
    }

    .search-input::placeholder {
        color: #b9b9c3;
        font-weight: 600;
    }

    .filters {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-right: 6px;
        flex: 0 0 auto;
        white-space: nowrap;
    }

    .filter-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 22px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        color: #fff;
        background: var(--orange);
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
        text-decoration: none;
        line-height: 1;
        transition: background .2s ease, transform .1s ease;
    }

    .filter-pill.active {
        background: var(--green);
    }

    .filter-pill:hover {
        transform: translateY(-1px);
    }

    /* Table shell (WIDER / LANDSCAPE) */
    .table-shell {
        margin: 22px auto 0;
        width: min(1100px, calc(100% - 160px));
        /* wider */
        background: linear-gradient(180deg, #20055f 0%, #15024b 100%);
        border-radius: 14px;
        padding: 22px 22px 26px;
        box-shadow: 0 22px 40px rgba(0, 0, 0, .22);
        flex: 1;
        display: flex;
    }

    .table-inner {
        background: #fff;
        border-radius: 12px;
        padding: 18px 16px 20px;
        border: 1px solid rgba(0, 0, 0, .06);
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 420px;
        /* helps “landscape feel” even when empty */
    }

    .head-pills {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr 1fr 28px;
        /* roomier */
        gap: 14px;
        align-items: center;
        padding: 0 10px 12px;
    }

    .head-pill {
        height: 28px;
        /* bigger */
        border-radius: 10px;
        background: var(--purple-3);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        /* bigger */
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .2px;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .14);
    }

    .rows {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 8px;
        flex: 1;
    }

    .row-card {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr 1fr 28px;
        gap: 14px;
        align-items: center;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 12px;
        padding: 14px 14px;
        /* bigger */
        background: #fff;
        box-shadow: 0 2px 0 rgba(0, 0, 0, .03);
        text-align: center;
    }

    .cell {
        font-size: 16px;
        /* bigger */
        font-weight: 700;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cell.muted {
        color: #2d2d2d;
        font-weight: 700;
    }

    /* Simple 3 vertical dots */
    .kebab {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
    }

    .kebab details {
        position: relative;
    }

    .kebab summary {
        list-style: none;
        cursor: pointer;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        padding: 0;
    }

    .kebab summary::-webkit-details-marker {
        display: none;
    }

    .dots {
        width: 7px;
        height: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .dots span {
        width: 6px;
        height: 6px;
        background: var(--purple-3);
        border-radius: 50%;
        display: block;
    }

    .menu {
        position: absolute;
        right: 0;
        top: 26px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 12px;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .18);
        min-width: 170px;
        overflow: hidden;
        z-index: 10;
    }

    .menu a,
    .menu button {
        display: block;
        width: 100%;
        text-align: left;
        padding: 12px 14px;
        font-size: 15px;
        /* bigger */
        font-weight: 800;
        color: var(--ink);
        background: #fff;
        border: none;
        text-decoration: none;
        cursor: pointer;
    }

    .menu a:hover,
    .menu button:hover {
        background: #f4f4f7;
    }

    .empty {
        margin: auto 0;
        text-align: center;
        padding: 22px 10px;
        color: #444;
        font-weight: 700;
        font-size: 16px;
    }

    /* Responsive: keep it usable on smaller screens */
    @media (max-width: 900px) {

        .search-row,
        .table-shell {
            width: calc(100% - 48px);
        }

        .filters {
            gap: 10px;
        }

        .filter-pill {
            padding: 0 16px;
            height: 32px;
        }
    }

    @media (max-width: 680px) {
        .search-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filters {
            justify-content: center;
            padding-right: 0;
        }
    }
</style>

<div class="contents-wrap">
    <div class="contents-inner">
        <div class="top-row">
            <a class="new-post-btn" href="{{ route('user.contents.create') }}">NEW POST</a>

            <div class="title-block">
                <h1>Manage your contents here!</h1>
                <p>Browse published articles and explore shared ideas.</p>
            </div>

            <div style="width:110px;"></div>
        </div>

        {{-- Search + Filters (GET request) --}}
        <form class="search-row" method="GET" action="{{ route('user.contents.index') }}">
            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M10.5 18.5a8 8 0 1 1 0-16 8 8 0 0 1 0 16Z" stroke="#111" stroke-width="2" />
                    <path d="M16.5 16.5 21 21" stroke="#111" stroke-width="2" stroke-linecap="round" />
                </svg>

                <input class="search-input"
                    type="text"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Search content..." />
            </div>

            <div class="filters">
                <a class="filter-pill {{ $status === 'all' ? 'active' : '' }}"
                    href="{{ route('user.contents.index', array_filter(['q' => $q, 'status' => 'all'])) }}">
                    ALL
                </a>

                <a class="filter-pill {{ $status === 'draft' ? 'active' : '' }}"
                    href="{{ route('user.contents.index', array_filter(['q' => $q, 'status' => 'draft'])) }}">
                    DRAFT
                </a>

                <a class="filter-pill {{ $status === 'published' ? 'active' : '' }}"
                    href="{{ route('user.contents.index', array_filter(['q' => $q, 'status' => 'published'])) }}">
                    PUBLISHED
                </a>
            </div>
        </form>

        <div class="table-shell">
            <div class="table-inner">
                <div class="head-pills">
                    <div class="head-pill">TITLE</div>
                    <div class="head-pill">AUTHOR</div>
                    <div class="head-pill">CATEGORIES</div>
                    <div class="head-pill">UPDATED</div>
                    <div></div>
                </div>

                <div class="rows">
                    @forelse($articles as $article)
                    <div class="row-card"
                        data-title="{{ strtolower($article->title) }}"
                        data-author="{{ strtolower(optional($article->author)->name ?? (auth()->user()->name ?? 'you')) }}"
                        data-category="{{ strtolower(optional($article->category)->name ?? '') }}"
                        data-status="{{ strtolower($article->status ?? '') }}">
                        <div class="cell" title="{{ $article->title }}">
                            {{ $article->title }}
                        </div>

                        <div class="cell muted" title="{{ optional($article->author)->name ?? (auth()->user()->name ?? 'You') }}">
                            {{ optional($article->author)->name ?? (auth()->user()->name ?? 'You') }}
                        </div>

                        <div class="cell" title="{{ optional($article->category)->name ?? '—' }}">
                            {{ optional($article->category)->name ?? '—' }}
                        </div>

                        <div class="cell muted">
                            {{ optional($article->updated_at)->format('F j, Y') }}
                        </div>

                        <div class="kebab">
                            <details>
                                <summary aria-label="Actions">
                                    <span class="dots" aria-hidden="true">
                                        <span></span><span></span><span></span>
                                    </span>
                                </summary>

                                <div class="menu">
                                    <a href="{{ route('user.contents.show', $article->id) }}">View</a>
                                    <a href="{{ route('user.contents.edit', $article->id) }}">Edit</a>

                                    <form method="POST" action="{{ route('user.contents.destroy', $article->id) }}"
                                        onsubmit="return confirm('Delete this content?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete</button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    </div>
                    @empty
                    <div class="empty">
                        No contents found. Click <b>NEW POST</b> to create your first one.
                    </div>
                    @endforelse
                </div>

                @if(method_exists($articles, 'links'))
                <div style="margin-top:14px;">
                    {{ $articles->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const input = document.querySelector('.search-input');
        const rows = Array.from(document.querySelectorAll('.row-card'));

        if (!input || rows.length === 0) return;

        function filterRows() {
            const q = (input.value || '').trim().toLowerCase();

            rows.forEach(row => {
                const title = row.dataset.title || '';
                const author = row.dataset.author || '';
                const category = row.dataset.category || '';

                const match = !q ||
                    title.includes(q) ||
                    author.includes(q) ||
                    category.includes(q);

                row.style.display = match ? '' : 'none';
            });
        }

        input.addEventListener('input', filterRows);
    })();
</script>

@endsection