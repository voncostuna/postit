{{--
|--------------------------------------------------------------------------
| ADMIN CREATE CONTENT
|--------------------------------------------------------------------------
| FIXED:
| - TITLE pill is now GUARANTEED inside the input (no floating outside)
|   by using a dedicated wrapper (.field-shell) with internal padding.
| - CONTENT pill stays correct (inside)
| - Keeps right-side purple accent + right-side action buttons
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
$oldTitle = old('title', '');
$oldContent = old('content', '');
$oldCategory = old('category_id', '');
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
    .create-wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 24px 32px 32px;
        overflow: hidden;
        background: #fff;
    }

    .create-wrap::before {
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

    .create-wrap::after {
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

    .create-inner {
        position: relative;
        z-index: 1;
        width: min(1100px, calc(100% - 120px));
        margin: 0 auto;
        display: flex;
        flex-direction: column;
    }

    /* ===== Header row (title left, actions right) ===== */
    .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 6px 6px 0;
    }

    .title-block {
        text-align: left;
        padding-left: 6px;
        max-width: 640px;
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

    .top-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 10px;
        white-space: nowrap;
    }

    .action-pill {
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
        letter-spacing: .2px;
        color: #fff;
        background: var(--orange);
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
        text-decoration: none;
        line-height: 1;
        transition: transform .1s ease;
    }

    .action-pill:hover {
        transform: translateY(-1px);
    }

    .cancel-pill {
        background: var(--purple-3);
    }

    .draft-pill {
        background: var(--orange);
    }

    .publish-pill {
        background: var(--orange);
    }

    /* ===== Title + buttons row ===== */
    .title-row {
        margin-top: 14px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 0 6px;
    }

    .title-field {
        flex: 1;
        min-width: 420px;
    }

    .field-shell {
        position: relative;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .14);
        background: #fff;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .10);
        padding: 18px 14px 16px;
        min-height: 64px;
    }

    .label-pill {
        position: absolute;
        left: 14px;
        top: 8px;
        height: 22px;
        padding: 0 12px;
        border-radius: 999px;
        background: var(--purple-3);
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        letter-spacing: .2px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, .12);
        z-index: 2;
        pointer-events: none;
    }

    .text-input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 16px;
        font-weight: 700;
        color: var(--ink);
        background: transparent;
        padding: 20px 0 0;
        height: 44px;
        line-height: 26px;
    }

    .text-input::placeholder {
        color: #b9b9c3;
        font-weight: 700;
    }

    .right-buttons {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
        padding-top: 6px;
    }

    .category-wrap {
        position: relative;
    }

    .ghost-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 18px;
        border-radius: 999px;
        border: 1px solid rgba(0, 0, 0, .18);
        background: var(--orange);
        color: white;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .08);
        white-space: nowrap;
    }

    .ghost-pill:hover,
    .ghost-pill.is-active {
        background: var(--green);
        color: #fff;
    }

    .file-input {
        display: none;
    }

    .file-name {
        max-width: 200px;
        font-size: 14px;
        font-weight: 700;
        color: #20055f;
        opacity: .85;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .remove-pill {
        border-color: rgba(217, 83, 79, .40);
        color: white;
    }

    .category-menu {
        position: absolute;
        right: 0;
        /* keep your current alignment */
        top: 44px;
        width: 220px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, .12);
        box-shadow: 0 18px 40px rgba(0, 0, 0, .18);
        overflow: hidden;
        display: none;
        z-index: 50;
    }

    .category-menu.show {
        display: block;
    }

    .category-menu-head {
        padding: 10px 12px;
        font-weight: 900;
        color: var(--purple-3);
        border-bottom: 1px solid rgba(0, 0, 0, .08);
        background: rgba(30, 15, 82, .04);
    }

    .category-list {
        padding: 10px;
        max-height: 220px;
        overflow-y: auto;
    }

    /* optional: nicer scrollbar */
    .category-list::-webkit-scrollbar {
        width: 8px;
    }

    .category-list::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, .18);
        border-radius: 999px;
    }

    .category-list::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, .06);
    }

    .category-item {
        width: 100%;
        text-align: center;
        padding: 10px 10px;
        border-radius: 12px;
        border: none;
        background: #e85f1a;
        cursor: pointer;

        font-weight: 900;
        color: #fff;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: .4px;

        margin-bottom: 10px;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
    }

    .category-item:last-child {
        margin-bottom: 0;
    }

    .category-item:hover {
        filter: brightness(1.03);
        transform: translateY(-1px);
    }

    .category-item.active {
        outline: 3px solid rgba(14, 143, 1, .30);
    }

    .category-empty {
        padding: 12px 10px;
        color: #6a6a6a;
        font-weight: 700;
        font-size: 14px;
        text-align: center;
    }

    /* Content shell */
    .content-shell {
        margin-top: 28px;
        width: 100%;
        background: linear-gradient(180deg, #20055f 0%, #15024b 100%);
        border-radius: 14px;
        padding: 22px 22px 26px;
        box-shadow: 0 22px 40px rgba(0, 0, 0, .22);
        display: flex;
    }

    .content-inner {
        background: #fff;
        border-radius: 12px;
        padding: 18px 16px 20px;
        border: 1px solid rgba(0, 0, 0, .06);
        flex: 1;
    }

    .content-field {
        position: relative;
        padding-top: 22px;
    }

    .content-label {
        position: absolute;
        left: 16px;
        top: -8px;
        height: 22px;
        padding: 0 12px;
        border-radius: 999px;
        background: var(--purple-3);
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        letter-spacing: .2px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, .12);
        z-index: 2;
        pointer-events: none;
    }

    .textarea {
        width: 100%;
        min-height: 420px;
        resize: none;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .14);
        padding: 14px 14px;
        outline: none;
        font-size: 16px;
        font-weight: 650;
        color: #111;
        line-height: 1.6;
        background: #fff;
    }

    .textarea::placeholder {
        color: #b9b9c3;
        font-weight: 650;
    }

    @media (max-width: 900px) {
        .create-inner {
            width: calc(100% - 48px);
        }

        .title-field {
            min-width: 260px;
        }
    }
</style>

<div class="create-wrap">
    <div class="create-inner">

        <form method="POST"
            action="{{ route('admin.contents.store') }}"
            enctype="multipart/form-data"
            style="display:flex; flex-direction:column; flex:1;">
            @csrf

            <div class="topbar">
                <div class="title-block">
                    <h1>Create another post!</h1>
                    <p>Create and share your ideas with the community.</p>
                </div>

                <div class="top-actions">
                    <a href="{{ route('admin.contents.index') }}" class="action-pill cancel-pill">CANCEL</a>
                    <button class="action-pill draft-pill" type="submit" name="status" value="draft">SAVE DRAFT</button>
                    <button class="action-pill publish-pill" type="submit" name="status" value="published">PUBLISHED</button>
                </div>
            </div>

            <div class="title-row">
                <div class="title-field">
                    <div class="field-shell">
                        <span class="label-pill">TITLE</span>
                        <input
                            class="text-input"
                            type="text"
                            name="title"
                            value="{{ $oldTitle }}"
                            placeholder="Enter post title..."
                            required />
                    </div>
                </div>

                <div class="right-buttons">
                    {{-- Categories dropdown --}}
                    <div class="category-wrap" id="categoryWrap">
                        <button type="button" class="ghost-pill" id="categoryBtn" aria-haspopup="listbox" aria-expanded="false">
                            CATEGORIES
                        </button>

                        <input type="hidden" name="category_id" id="categoryInput" value="{{ $oldCategory }}">

                        <div class="category-menu" id="categoryMenu" role="listbox" tabindex="-1" aria-label="Categories">

                            <div class="category-list">
                                @isset($categories)
                                @forelse($categories as $cat)
                                <button type="button"
                                    class="category-item"
                                    data-id="{{ $cat->id }}"
                                    data-name="{{ strtoupper($cat->name) }}">
                                    {{ strtoupper($cat->name) }}
                                </button>
                                @empty
                                <div class="category-empty">No categories yet</div>
                                @endforelse
                                @else
                                <div class="category-empty">No categories provided</div>
                                @endisset
                            </div>
                        </div>
                    </div>

                    {{-- Upload image --}}
                    <label class="ghost-pill" for="imageInput" style="cursor:pointer;">UPLOAD IMAGE</label>
                    <input id="imageInput" class="file-input" type="file" name="featured_image" accept="image/*" />
                    <span id="imageName" class="file-name" aria-live="polite">No file chosen</span>

                    <button type="button" id="removeImageBtn" class="ghost-pill remove-pill" style="display:none;">
                        REMOVE
                    </button>
                </div>
            </div>

            <div class="content-shell">
                <div class="content-inner">
                    <div class="content-field">
                        <span class="content-label">CONTENT</span>

                        <textarea class="textarea"
                            name="content"
                            placeholder="Write your content here...."
                            required>{{ $oldContent }}</textarea>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
    (function() {
        // Upload image filename feedback
        const input = document.getElementById('imageInput');
        const label = document.getElementById('imageName');
        const removeBtn = document.getElementById('removeImageBtn');

        if (input && label && removeBtn) {
            input.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    label.textContent = this.files[0].name;
                    removeBtn.style.display = 'inline-flex';
                } else {
                    resetImage();
                }
            });

            removeBtn.addEventListener('click', function() {
                resetImage();
            });

            function resetImage() {
                input.value = '';
                label.textContent = 'No file chosen';
                removeBtn.style.display = 'none';
            }
        }

        // Categories dropdown
        const wrap = document.getElementById('categoryWrap');
        const btn = document.getElementById('categoryBtn');
        const menu = document.getElementById('categoryMenu');
        const catInput = document.getElementById('categoryInput');

        if (wrap && btn && menu && catInput) {
            function openMenu() {
                menu.classList.add('show');
                btn.setAttribute('aria-expanded', 'true');
            }

            function closeMenu() {
                menu.classList.remove('show');
                btn.setAttribute('aria-expanded', 'false');
            }

            btn.addEventListener('click', function() {
                menu.classList.contains('show') ? closeMenu() : openMenu();
            });

            menu.addEventListener('click', function(e) {
                const item = e.target.closest('.category-item');
                if (!item) return;

                catInput.value = item.dataset.id;
                btn.textContent = item.dataset.name;

                menu.querySelectorAll('.category-item').forEach(x => x.classList.remove('active'));
                item.classList.add('active');

                closeMenu();
            });

            document.addEventListener('click', function(e) {
                if (!wrap.contains(e.target)) closeMenu();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });

            // Restore old selected category
            const existing = catInput.value;
            if (existing) {
                const activeBtn = menu.querySelector(`.category-item[data-id="${existing}"]`);
                if (activeBtn) {
                    activeBtn.classList.add('active');
                    btn.textContent = activeBtn.dataset.name;
                }
            }
        }
    })();
</script>
@endsection