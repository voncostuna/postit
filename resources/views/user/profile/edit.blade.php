{{--
|-------------------------------------------------------------------------- 
| USER PROFILE
|-------------------------------------------------------------------------- 
| Profile page UI (real data only)
|
| Update:
| - Add confirmation modal for LOGOUT
| - Add confirmation modal for DELETE ACCOUNT (replaces browser confirm)
|-------------------------------------------------------------------------- 
--}}
@extends('layouts.app')

@push('styles')
<style>
    :root {
        --postit-green: #0B7A0B;
        --postit-purple: #1E0F52;
        --postit-orange: #E05A1B;
    }

    main.container.py-4 {
        max-width: 100% !important;
        padding: 0 !important;
    }

    .profile-wrap {
        position: relative;
        min-height: calc(100vh - 90px);
        overflow: hidden;
        background: radial-gradient(1200px 700px at 25% 0%, #ffffff 0%, #fbfbfb 45%, #f4f4f4 100%);
        padding: 26px 22px 48px;
    }

    .profile-accent {
        position: absolute;
        right: -140px;
        top: -120px;
        width: 520px;
        height: 520px;
        border-radius: 0 0 0 520px;
        background: radial-gradient(circle at 28% 28%, #ff8a3d 0%, #f36a21 40%, #e05a1b 72%, #c94b12 100%);
        z-index: 0;
        pointer-events: none;
    }

    .profile-main {
        position: relative;
        z-index: 2;
        max-width: 1040px;
        margin: 0 auto;
    }

    .top-row {
        display: grid;
        grid-template-columns: 1fr 220px;
        gap: 22px;
    }

    .profile-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, .12);
        padding: 22px;
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 22px;
    }

    .avatar-big {
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: var(--postit-green);
    }

    .profile-info h1 {
        font-size: 42px;
        font-weight: 900;
        color: var(--postit-green);
        margin: 0;
    }

    .profile-handle {
        font-weight: 800;
        opacity: .85;
        margin-bottom: 12px;
    }

    .actions {
        padding-top: 58px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: flex-end;
    }

    .action-btn {
        width: 180px;
        height: 34px;
        border-radius: 999px;
        border: none;
        font-weight: 900;
        cursor: pointer;
    }

    .btn-logout {
        background: var(--postit-green);
        color: #fff;
    }

    .btn-delete {
        background: var(--postit-purple);
        color: #fff;
    }

    .activity-shell {
        margin-top: 22px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, .12);
        padding: 18px;
    }

    .activity-head {
        font-weight: 900;
        color: var(--postit-purple);
        margin-bottom: 14px;
    }

    .act-item {
        background: var(--postit-purple);
        color: #fff;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        gap: 12px;
        margin-top: 12px;
    }

    .act-avatar {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        background: #fff;
        color: var(--postit-purple);
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state {
        padding: 24px;
        text-align: center;
        font-weight: 700;
        color: #888;
    }

    /* ===== Modal ===== */
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
        background: var(--postit-orange);
    }

    .modal-confirm-logout {
        background: var(--postit-green);
    }

    .modal-confirm-delete {
        background: var(--postit-purple);
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
                {{-- LOGOUT (modal confirm) --}}
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="button" class="action-btn btn-logout" id="openLogoutModal">LOGOUT</button>
                </form>

                {{-- DELETE ACCOUNT (modal confirm) --}}
                <form method="POST" action="{{ route('user.profile.destroy') }}" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="action-btn btn-delete" id="openDeleteModal">DELETE ACCOUNT</button>
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

{{-- LOGOUT MODAL --}}
<div id="logoutModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
        <h3 id="logoutModalTitle" class="modal-title">Log out?</h3>
        <p class="modal-text">You’ll be signed out of your account.</p>

        <div class="modal-actions">
            <button type="button" class="modal-btn modal-cancel" id="cancelLogoutBtn">Cancel</button>
            <button type="button" class="modal-btn modal-confirm-logout" id="confirmLogoutBtn">Logout</button>
        </div>
    </div>
</div>

{{-- DELETE ACCOUNT MODAL --}}
<div id="deleteModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <h3 id="deleteModalTitle" class="modal-title">Delete account?</h3>
        <p class="modal-text">This action can’t be undone. All your data may be removed.</p>

        <div class="modal-actions">
            <button type="button" class="modal-btn modal-cancel" id="cancelDeleteBtn">Cancel</button>
            <button type="button" class="modal-btn modal-confirm-delete" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<script>
    (function() {
        const logoutForm = document.getElementById('logoutForm');
        const deleteForm = document.getElementById('deleteForm');

        const logoutModal = document.getElementById('logoutModal');
        const deleteModal = document.getElementById('deleteModal');

        const openLogout = document.getElementById('openLogoutModal');
        const openDelete = document.getElementById('openDeleteModal');

        const cancelLogout = document.getElementById('cancelLogoutBtn');
        const confirmLogout = document.getElementById('confirmLogoutBtn');

        const cancelDelete = document.getElementById('cancelDeleteBtn');
        const confirmDelete = document.getElementById('confirmDeleteBtn');

        let activeModal = null;

        function openModal(modal, focusEl) {
            activeModal = modal;
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            if (focusEl) focusEl.focus();
        }

        function closeModal(modal) {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (activeModal === modal) activeModal = null;
        }

        // Open modals
        if (openLogout) openLogout.addEventListener('click', () => openModal(logoutModal, cancelLogout));
        if (openDelete) openDelete.addEventListener('click', () => openModal(deleteModal, cancelDelete));

        // Confirm actions
        if (confirmLogout) confirmLogout.addEventListener('click', () => logoutForm && logoutForm.submit());
        if (confirmDelete) confirmDelete.addEventListener('click', () => deleteForm && deleteForm.submit());

        // Cancel buttons
        if (cancelLogout) cancelLogout.addEventListener('click', () => closeModal(logoutModal));
        if (cancelDelete) cancelDelete.addEventListener('click', () => closeModal(deleteModal));

        // Click outside to close
        [logoutModal, deleteModal].forEach(modal => {
            if (!modal) return;
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal(modal);
            });
        });

        // Escape to close whichever modal is open
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape' || !activeModal) return;
            closeModal(activeModal);
        });
    })();
</script>
@endsection