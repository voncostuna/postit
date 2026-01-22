{{--
|--------------------------------------------------------------------------
| CREATE USER CONTENT
|--------------------------------------------------------------------------
| Form for creating a new content item (Create Post page)
|
| UI: Matches the provided mock (green gradient bg, orange action pills,
|     purple shells, pill labels TITLE/CONTENT, categories dropdown + upload)
|
| Notes:
| - Uses two submit buttons: status=draft or status=published
| - Categories dropdown is dynamic via $categories (from DB)
| - Selected category stored in hidden input: category_id
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
// Optional defaults (in case of validation redirect)
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

    /* ===== Page Canvas (Green) ===== */
    .create-wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 22px 28px 28px;
        overflow: hidden;
        background:
            radial-gradient(900px 520px at 25% 5%, rgba(255, 255, 255, .18) 0%, rgba(255, 255, 255, 0) 55%),
            linear-gradient(180deg, #10b600 0%, #0a8c00 55%, #066300 100%);
    }

    /* big white curves on both sides */
    .create-wrap::before {
        content: "";
        position: absolute;
        left: -260px;
        top: 130px;
        width: 520px;
        height: 520px;
        background: #fff;
        border-radius: 50%;
        z-index: 0;
    }

    .create-wrap::after {
        content: "";
        position: absolute;
        right: -260px;
        top: 150px;
        width: 520px;
        height: 520px;
        background: #fff;
        border-radius: 50%;
        z-index: 0;
    }

    .create-inner {
        position: relative;
        z-index: 1;
        min-height: calc(100vh - 30px);
        display: flex;
        flex-direction: column;
    }

    /* ===== Top Bar ===== */
    .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 6px 6px 0;
    }

    .top-actions {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-top: 10px;
    }

    .action-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 26px;
        border-radius: 999px;
        background: var(--orange);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .2px;
        border: none;
        cursor: pointer;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .18);
        white-space: nowrap;
    }

    .cancel-pill {
        background: rgba(255, 255, 255, .25);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .35);
        box-shadow: 0 8px 16px rgba(0, 0, 0, .12);
        text-decoration: none;
    }

    .title-block {
        width: 100%;
        text-align: right;
        padding-right: 10px;
    }

    .title-block h1 {
        margin: 0;
        font-size: 40px;
        font-weight: 900;
        color: #fff;
        line-height: 1.08;
    }

    .title-block p {
        margin: 6px 0 0;
        font-size: 16px;
        font-weight: 700;
        color: #eaffea;
        opacity: .95;
    }

    /* ===== Title Row (input + buttons) ===== */
    .title-row {
        margin: 18px auto 18px;
        width: min(1100px, calc(100% - 120px));
        background: #fff;
        border-radius: 12px;
        padding: 22px 18px 22px;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .16);
        border: 1px solid rgba(0, 0, 0, .10);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .title-field {
        flex: 1;
        position: relative;
        padding-top: 28px;
    }

    .label-pill {
        position: absolute;
        left: 14px;
        top: -10px;
        height: 28px;
        padding: 0 26px;
        border-radius: 10px;
        background: var(--purple-3);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .14);
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .text-input {
        width: 100%;
        height: 54px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .20);
        padding: 0 16px;
        font-size: 16px;
        font-weight: 600;
        outline: none;
        background: #fff;
    }

    .text-input::placeholder {
        color: #b9b9c3;
        font-weight: 600;
    }

    .right-buttons {
        display: flex;
        gap: 16px;
        align-items: center;
        white-space: nowrap;
    }

    .ghost-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 26px;
        border-radius: 999px;
        background: var(--orange);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .2px;
        text-decoration: none;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .remove-pill {
        background: #d9534f;
        /* soft red */
        box-shadow: 0 10px 18px rgba(217, 83, 79, .25);
    }

    /* hidden inputs */
    .file-input {
        display: none;
    }

    .file-name {
        display: inline-block;
        max-width: 240px;
        font-size: 14px;
        font-weight: 600;
        color: #3d3d3d;
        opacity: .85;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-left: 8px;
        transform: translateY(1px);
    }

    /* ===== Categories Dropdown (dynamic) ===== */
    .category-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .category-menu {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: 220px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 12px;
        box-shadow: 0 18px 34px rgba(0, 0, 0, .18);
        padding: 12px 12px 14px;
        display: none;
        overflow-x: hidden;
        z-index: 50;
    }

    .category-menu.show {
        display: block;
    }

    .category-menu-head {
        height: 28px;
        border-radius: 10px;
        background: var(--green);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .14);
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .category-item {
        width: 100%;
        border: none;
        cursor: pointer;
        height: 30px;
        border-radius: 999px;
        background: var(--orange);
        color: #fff;
        font-weight: 900;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .2px;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .10);
        margin: 8px 0 0;
    }

    .category-item:hover {
        transform: translateY(-1px);
    }

    .category-item.active {
        background: var(--purple-3);
    }

    .category-empty {
        padding: 10px 6px;
        font-size: 14px;
        font-weight: 600;
        color: #444;
        opacity: .85;
        text-align: center;
    }

    /* If list is long, scroll */
    .category-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 220px;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 4px;
    }

    /* ===== Content Shell ===== */
    .content-shell {
        margin: 0 auto;
        width: min(1100px, calc(100% - 120px));
        background: linear-gradient(180deg, #20055f 0%, #15024b 100%);
        border-radius: 14px;
        padding: 22px 22px 26px;
        box-shadow: 0 22px 40px rgba(0, 0, 0, .24);
        flex: 1;
        display: flex;
    }

    .content-inner {
        background: #fff;
        border-radius: 12px;
        padding: 18px 16px 20px;
        border: 1px solid rgba(0, 0, 0, .08);
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 520px;
        position: relative;
    }

    .content-field {
        position: relative;
        padding-top: 26px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .textarea {
        width: 100%;
        flex: 1;
        min-height: 420px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .20);
        padding: 16px 16px;
        font-size: 16px;
        font-weight: 600;
        outline: none;
        resize: none;
        background: #fff;
    }

    .textarea::placeholder {
        color: #c2c2cc;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 980px) {

        .title-row,
        .content-shell {
            width: calc(100% - 48px);
        }

        .title-block {
            text-align: center;
            padding-right: 0;
        }

        .topbar {
            flex-direction: column;
            align-items: center;
        }

        .top-actions {
            margin-top: 0;
        }
    }

    @media (max-width: 720px) {
        .title-row {
            flex-direction: column;
            align-items: stretch;
        }

        .right-buttons {
            justify-content: center;
            flex-wrap: wrap;
        }

        .category-menu {
            left: 50%;
            transform: translateX(-50%);
        }
    }
</style>

<div class="create-wrap">
    <div class="create-inner">

        <form method="POST" action="{{ route('user.contents.store') }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; flex:1;">
            @csrf

            <div class="topbar">
                <div class="top-actions">
                    <a href="{{ route('user.contents.index') }}" class="action-pill cancel-pill">
                        CANCEL
                    </a>

                    <button class="action-pill" type="submit" name="status" value="draft">
                        SAVE DRAFT
                    </button>

                    <button class="action-pill" type="submit" name="status" value="published">
                        PUBLISHED
                    </button>
                </div>

                <div class="title-block">
                    <h1>Create another post!</h1>
                    <p>Create and share your ideas with the community.</p>
                </div>
            </div>

            <div class="title-row">
                <div class="title-field">
                    <span class="label-pill">TITLE</span>
                    <input
                        class="text-input"
                        type="text"
                        name="title"
                        value="{{ $oldTitle }}"
                        placeholder="Enter post title..."
                        required />
                </div>

                <div class="right-buttons">
                    {{-- Categories dropdown (dynamic from DB via $categories) --}}
                    <div class="category-wrap" id="categoryWrap">
                        <button type="button" class="ghost-pill" id="categoryBtn" aria-haspopup="listbox" aria-expanded="false">
                            CATEGORIES
                        </button>

                        <input type="hidden" name="category_id" id="categoryInput" value="{{ $oldCategory }}">

                        <div class="category-menu" id="categoryMenu" role="listbox" tabindex="-1" aria-label="Categories">
                            <div class="category-menu-head">CATEGORIES</div>

                            <div class="category-list">
                                @isset($categories)
                                @forelse($categories as $cat)
                                <button
                                    type="button"
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

                    {{-- Upload image button (clicks hidden input) --}}
                    <label class="ghost-pill" for="imageInput" style="cursor:pointer;">
                        UPLOAD IMAGE
                    </label>
                    <input id="imageInput" class="file-input" type="file" name="featured_image" accept="image/*" />

                    <span id="imageName" class="file-name" aria-live="polite">No file chosen</span>

                    <button
                        type="button"
                        id="removeImageBtn"
                        class="ghost-pill remove-pill"
                        style="display:none;">
                        REMOVE
                    </button>
                </div>
            </div>

            <div class="content-shell">
                <div class="content-inner">
                    <div class="content-field">
                        <span class="label-pill" style="left:16px; top:-10px;">CONTENT</span>

                        <textarea
                            class="textarea"
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
        // ===== Upload image filename feedback =====
        const input = document.getElementById('imageInput');
        const label = document.getElementById('imageName');
        const removeBtn = document.getElementById('removeImageBtn');

        if (!input || !label || !removeBtn) return;

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

        // ===== Categories dropdown =====
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

                const id = item.dataset.id;
                const name = item.dataset.name;

                catInput.value = id;
                btn.textContent = name;

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

            // On load: if old(category_id) exists, mark it active + set label
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