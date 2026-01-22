{{--
|--------------------------------------------------------------------------
| ADMIN CONTENT LIST
|--------------------------------------------------------------------------
| Updates requested:
| - Keep NEW POST button (top-right)
| - Move bluish/purple background accent to the RIGHT side (instead of left)
| - Title + subtitle must be LEFT aligned (on the left side)
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
        --orange: #e85f1a;
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

    .contents-wrap::before {
        content: "";
        position: absolute;
        right: -180px;
        top: -160px;
        width: 640px;
        height: 520px;
        border-radius: 0 0 0 640px;
        background: radial-gradient(circle at 28% 28%,
                var(--purple-1) 0%,
                #4c1fff 40%,
                #2f0fb8 72%,
                #1d0a6f 100%);
        z-index: 0;
        pointer-events: none;
        filter: drop-shadow(0 18px 40px rgba(0, 0, 0, .10));
    }

    .contents-wrap::after {
        content: "";
        position: absolute;
        right: -260px;
        bottom: -260px;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: linear-gradient(145deg, #24055e 0%, #12002f 100%);
        z-index: 0;
        pointer-events: none;
        opacity: .95;
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

    .title-block {
        width: 100%;
        text-align: left;
        margin-top: 2px;
        margin-left: 100px;
        padding-left: 6px;
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

    /* Search + filters bar */
    .search-row {
        margin: 18px auto 22px;
        width: min(980px, calc(100% - 140px));
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
        padding: 0 14px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .14);
    }

    .search-icon {
        width: 22px;
        height: 22px;
        opacity: .85;
        flex: 0 0 auto;
    }

    .search-input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 16px;
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

    /* Table shell */
    .table-shell {
        margin: 22px auto 0;
        width: min(1100px, calc(100% - 160px));
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
    }

    .head-pills {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr 1fr 28px;
        gap: 14px;
        align-items: center;
        padding: 0 10px 12px;
    }

    .head-pill {
        height: 28px;
        border-radius: 10px;
        background: var(--purple-3);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
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
        background: #fff;
        box-shadow: 0 2px 0 rgba(0, 0, 0, .03);
    }

    .cell {
        font-size: 16px;
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

    /* Kebab */
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

    /* Modal */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        display: none;
        place-items: center;
        background: rgba(0, 0, 0, .45);
        z-index: 9999;
        padding: 16px;
    }

    .modal-backdrop.show {
        display: grid;
    }

    .modal-card {
        width: min(520px, 100%);
        background: #fff;
        border-radius: 18px;
        padding: 18px 18px 16px;
        box-shadow: 0 18px 60px rgba(0, 0, 0, .22);
        border: 1px solid rgba(0, 0, 0, .10);
    }

    .modal-title {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 900;
        color: #111;
    }

    .modal-text {
        margin: 0 0 14px;
        opacity: .85;
        line-height: 1.35;
        font-weight: 700;
        color: #2d2d2d;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .modal-btn {
        height: 34px;
        padding: 0 18px;
        border-radius: 999px;
        border: none;
        font-weight: 900;
        cursor: pointer;
        color: #fff;
    }

    .modal-cancel {
        background: var(--orange);
    }

    .modal-confirm {
        background: #d9534f;
    }

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
            {{-- Left title/subtitle --}}
            <div class="title-block">
                <h1>Contents</h1>
                <p>Browse all your published articles and blog posts in one place.</p>
            </div>

            {{-- NEW POST on right --}}
            <a class="new-post-btn" href="{{ route('admin.contents.create') }}">NEW POST</a>
        </div>

        {{-- Search + Filters --}}
        <form class="search-row" method="GET" action="{{ route('admin.contents.index') }}">
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
                    href="{{ route('admin.contents.index', array_filter(['q' => $q, 'status' => 'all'])) }}">
                    ALL
                </a>

                <a class="filter-pill {{ $status === 'draft' ? 'active' : '' }}"
                    href="{{ route('admin.contents.index', array_filter(['q' => $q, 'status' => 'draft'])) }}">
                    DRAFT
                </a>

                <a class="filter-pill {{ $status === 'published' ? 'active' : '' }}"
                    href="{{ route('admin.contents.index', array_filter(['q' => $q, 'status' => 'published'])) }}">
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
                    @php
                    $author = optional($article->author)->name ?? '—';
                    $category = optional($article->category)->name ?? '—';
                    @endphp

                    <div class="row-card"
                        data-title="{{ strtolower($article->title ?? '') }}"
                        data-author="{{ strtolower($author) }}"
                        data-category="{{ strtolower($category) }}"
                        data-status="{{ strtolower($article->status ?? '') }}">

                        <div class="cell" title="{{ $article->title }}">
                            {{ $article->title }}
                        </div>

                        <div class="cell muted" title="{{ $author }}">
                            {{ $author }}
                        </div>

                        <div class="cell" title="{{ $category }}">
                            {{ $category }}
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
                                    <a href="{{ route('admin.contents.show', $article->id) }}">View</a>
                                    <a href="{{ route('admin.contents.edit', $article->id) }}">Edit</a>

                                    <button type="button"
                                        class="openDeleteModalBtn"
                                        data-action="{{ route('admin.contents.destroy', $article->id) }}"
                                        data-title="{{ $article->title }}">
                                        Delete
                                    </button>
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

{{-- DELETE MODAL --}}
<div id="deleteModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <h3 id="deleteModalTitle" class="modal-title">Delete content?</h3>
        <p class="modal-text" id="deleteModalText">This action can’t be undone.</p>

        <div class="modal-actions">
            <button type="button" class="modal-btn modal-cancel" id="cancelDeleteBtn">Cancel</button>

            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        // Client-side filtering (optional)
        const input = document.querySelector('.search-input');
        const rows = Array.from(document.querySelectorAll('.row-card'));
        if (input && rows.length) {
            function filterRows() {
                const q = (input.value || '').trim().toLowerCase();
                rows.forEach(row => {
                    const title = row.dataset.title || '';
                    const author = row.dataset.author || '';
                    const category = row.dataset.category || '';
                    const match = !q || title.includes(q) || author.includes(q) || category.includes(q);
                    row.style.display = match ? '' : 'none';
                });
            }
            input.addEventListener('input', filterRows);
        }

        // Delete modal
        const modal = document.getElementById('deleteModal');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const deleteForm = document.getElementById('deleteForm');
        const modalText = document.getElementById('deleteModalText');

        function openModal(action, title) {
            deleteForm.setAttribute('action', action);
            modalText.textContent = `Delete "${title}"? This action can’t be undone.`;
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            cancelBtn.focus();
        }

        function closeModal() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.openDeleteModalBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                openModal(this.dataset.action, this.dataset.title || 'this content');
            });
        });

        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
        });
    })();
</script>
@endsection