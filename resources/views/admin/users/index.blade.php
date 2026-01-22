{{--
|--------------------------------------------------------------------------
| ADMIN USERS INDEX
|--------------------------------------------------------------------------
| COPY the exact UI in your screenshot:
| - Right-side purple accent
| - "Users" title + green subtitle (left aligned)
| - Search bar (same look as admin contents)
| - Two pill toggles: USERS (active) + CONTENT (link to admin contents)
| - Purple table shell + white inner table
| - Columns: NAME, EMAIL, ROLE, JOINED + kebab actions
| - Delete uses a modal confirm (soft delete)
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
$q = request('q', '');
$role = request('role', 'all');
$status = request('status', 'all');
$trashed = request('trashed', '0');
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
    .users-wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 24px 32px 32px;
        overflow: hidden;
        background: #fff;
    }

    /* RIGHT-side purple wedge */
    .users-wrap::before {
        content: "";
        position: absolute;
        right: -140px;
        top: -140px;
        width: 560px;
        height: 420px;
        background: linear-gradient(145deg, var(--purple-1) 0%, #3b0db6 55%, var(--purple-2) 100%);
        border-radius: 0 0 0 560px;
        z-index: 0;
        pointer-events: none;
    }

    /* bottom-right purple blob */
    .users-wrap::after {
        content: "";
        position: absolute;
        right: -260px;
        bottom: -260px;
        width: 520px;
        height: 520px;
        background: linear-gradient(145deg, #24055e 0%, #12002f 100%);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
        opacity: .95;
    }

    .users-inner {
        position: relative;
        z-index: 1;
        width: min(1100px, calc(100% - 140px));
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Header */
    .header-row {
        padding: 6px 6px 0;
    }

    .title-block {
        text-align: left;
        max-width: 720px;
    }

    .title-block h1 {
        margin: 0;
        font-size: 44px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.05;
        letter-spacing: -0.4px;
    }

    .title-block p {
        margin: 6px 0 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--green);
    }

    /* Search + toggle row */
    .search-row {
        margin: 8px auto 6px;
        width: 100%;
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

    .toggle-pills {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-right: 6px;
        flex: 0 0 auto;
        white-space: nowrap;
    }

    .toggle-pill {
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
        transition: transform .1s ease, background .2s ease;
    }

    .toggle-pill.active {
        background: var(--green);
    }

    .toggle-pill:hover {
        transform: translateY(-1px);
    }

    /* Table shell */
    .table-shell {
        margin: 8px auto 0;
        width: 100%;
        background: linear-gradient(180deg, #20055f 0%, #15024b 100%);
        border-radius: 14px;
        padding: 22px 22px 26px;
        box-shadow: 0 22px 40px rgba(0, 0, 0, .22);
        flex: 1;
        display: flex;
        min-height: 520px;
    }

    .table-inner {
        background: #fff;
        border-radius: 12px;
        padding: 18px 16px 20px;
        border: 1px solid rgba(0, 0, 0, .06);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .head-pills {
        display: grid;
        grid-template-columns: 1.1fr 1.3fr .9fr 1fr 28px;
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
        grid-template-columns: 1.1fr 1.3fr .9fr 1fr 28px;
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

    /* kebab */
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
        cursor: pointer;
    }

    .menu button:hover {
        background: #f4f4f7;
    }

    .menu .danger {
        color: #d9534f;
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
        background: rgba(0, 0, 0, .45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 24px;
    }

    .modal-backdrop.show {
        display: flex;
    }

    .modal {
        width: min(520px, 100%);
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 22px 60px rgba(0, 0, 0, .28);
        border: 1px solid rgba(0, 0, 0, .10);
        overflow: hidden;
    }

    .modal-head {
        padding: 16px 18px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 900;
        color: var(--ink);
        margin: 0;
    }

    .modal-close {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, .12);
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-body {
        padding: 0 18px 16px;
        color: #3c3c3c;
        font-weight: 650;
        font-size: 16px;
        line-height: 1.5;
    }

    .modal-actions {
        padding: 14px 18px 18px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        border-top: 1px solid rgba(0, 0, 0, .08);
        background: rgba(0, 0, 0, .02);
        flex-wrap: wrap;
    }

    .btn {
        height: 36px;
        padding: 0 22px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .2px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 22px rgba(0, 0, 0, .14);
    }

    .btn-cancel {
        background: #efeff4;
        color: #111;
        box-shadow: none;
        border: 1px solid rgba(0, 0, 0, .10);
    }

    .btn-danger {
        background: #d9534f;
        color: #fff;
    }

    @media (max-width: 900px) {
        .users-inner {
            width: calc(100% - 48px);
        }
    }

    @media (max-width: 680px) {
        .search-row {
            flex-direction: column;
            align-items: stretch;
        }

        .toggle-pills {
            justify-content: center;
            padding-right: 0;
        }

        .head-pills,
        .row-card {
            grid-template-columns: 1fr 1fr;
            grid-auto-rows: auto;
        }

        .kebab {
            justify-content: flex-end;
        }
    }
</style>

<div class="users-wrap">
    <div class="users-inner">

        <div class="header-row">
            <div class="title-block">
                <h1>Users</h1>
                <p>Manage user accounts and permissions.</p>
            </div>
        </div>

        {{-- Search + role filters --}}
        <form class="search-row" method="GET" action="{{ route('admin.users.index') }}">
            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M10.5 18.5a8 8 0 1 1 0-16 8 8 0 0 1 0 16Z" stroke="#111" stroke-width="2" />
                    <path d="M16.5 16.5 21 21" stroke="#111" stroke-width="2" stroke-linecap="round" />
                </svg>

                <input class="search-input"
                    type="text"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Search user..." />
            </div>

            <div class="toggle-pills">
                <a class="toggle-pill {{ $role === 'all' ? 'active' : '' }}"
                    href="{{ route('admin.users.index', array_filter(['q' => $q, 'role' => 'all'])) }}">
                    ALL
                </a>

                <a class="toggle-pill {{ $role === 'admin' ? 'active' : '' }}"
                    href="{{ route('admin.users.index', array_filter(['q' => $q, 'role' => 'admin'])) }}">
                    ADMINS
                </a>

                <a class="toggle-pill {{ $role === 'user' ? 'active' : '' }}"
                    href="{{ route('admin.users.index', array_filter(['q' => $q, 'role' => 'user'])) }}">
                    USERS
                </a>
            </div>
        </form>

        <div class="table-shell">
            <div class="table-inner">
                <div class="head-pills">
                    <div class="head-pill">NAME</div>
                    <div class="head-pill">EMAIL</div>
                    <div class="head-pill">ROLE</div>
                    <div class="head-pill">JOINED</div>
                    <div></div>
                </div>

                <div class="rows" id="rows">
                    @forelse($users as $u)
                    @php
                    $joined = optional($u->created_at)->format('F j, Y');
                    @endphp

                    <div class="row-card"
                        data-name="{{ strtolower($u->name ?? '') }}"
                        data-email="{{ strtolower($u->email ?? '') }}"
                        data-role="{{ strtolower($u->role ?? '') }}">

                        <div class="cell" title="{{ $u->name }}">
                            {{ $u->name }}
                        </div>

                        <div class="cell muted" title="{{ $u->email }}">
                            {{ $u->email }}
                        </div>

                        <div class="cell" title="{{ ucfirst($u->role) }}">
                            {{ ucfirst($u->role) }}
                        </div>

                        <div class="cell muted">
                            {{ $joined }}
                        </div>

                        <div class="kebab">
                            <details>
                                <summary aria-label="Actions">
                                    <span class="dots" aria-hidden="true">
                                        <span></span><span></span><span></span>
                                    </span>
                                </summary>

                                <div class="menu">
                                    <button type="button"
                                        class="danger js-open-delete"
                                        data-user-id="{{ $u->id }}"
                                        data-user-name="{{ $u->name }}">
                                        Delete
                                    </button>
                                </div>
                            </details>
                        </div>
                    </div>
                    @empty
                    <div class="empty">No users found.</div>
                    @endforelse
                </div>

                @if(method_exists($users, 'links'))
                <div style="margin-top:14px;">
                    {{ $users->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal (Soft Delete) --}}
<div class="modal-backdrop" id="deleteModal" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="deleteTitle">
        <div class="modal-head">
            <h2 class="modal-title" id="deleteTitle">Delete user?</h2>
            <button type="button" class="modal-close" id="deleteClose" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 6l12 12M18 6 6 18" stroke="#111" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <div id="deleteText">
                This will remove the user account.
            </div>
            <div style="margin-top:8px; color:#6a6a6a; font-weight:650;">
                (Soft delete — you can restore later if needed.)
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn btn-cancel" id="deleteCancel">Cancel</button>

            <form method="POST" id="deleteForm" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        // Client-side search filter (keeps the exact UI responsive)
        const input = document.querySelector('.search-input');
        const rows = Array.from(document.querySelectorAll('.row-card'));

        function filterRows() {
            if (!input) return;
            const q = (input.value || '').trim().toLowerCase();

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const role = row.dataset.role || '';

                const match = !q || name.includes(q) || email.includes(q) || role.includes(q);
                row.style.display = match ? '' : 'none';
            });
        }

        if (input && rows.length) {
            input.addEventListener('input', filterRows);
        }

        // Close other kebabs when one opens (clean UX)
        document.addEventListener('toggle', function(e) {
            if (e.target.tagName?.toLowerCase() !== 'details') return;
            if (!e.target.open) return;
            document.querySelectorAll('.kebab details[open]').forEach(d => {
                if (d !== e.target) d.removeAttribute('open');
            });
        }, true);

        // DELETE MODAL
        const modal = document.getElementById('deleteModal');
        const closeBtn = document.getElementById('deleteClose');
        const cancelBtn = document.getElementById('deleteCancel');
        const form = document.getElementById('deleteForm');
        const text = document.getElementById('deleteText');

        function openModal(userId, userName) {
            // IMPORTANT: update to your resource route
            form.action = "{{ url('/admin/users') }}/" + userId;

            text.textContent = `Are you sure you want to delete "${userName}"?`;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.js-open-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                // close kebab
                const details = this.closest('details');
                if (details) details.removeAttribute('open');

                openModal(this.dataset.userId, this.dataset.userName || 'this user');
            });
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
        });
    })();
</script>
@endsection