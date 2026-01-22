{{--
|--------------------------------------------------------------------------
| EDIT USER CONTENT
|--------------------------------------------------------------------------
| Fix: image not showing because this blade was using $article->image.
| Your DB column is featured_image, so we build the URL via Storage::url().
|
| Also fix: file input name must be featured_image (matches controller validation)
| and remove_image should be handled by controller (optional note below).
|--------------------------------------------------------------------------
--}}
@extends('layouts.app')

@section('content')
@php
use Illuminate\Support\Facades\Storage;

$authorName = optional($article->author)->name ?? (auth()->user()->name ?? 'You');
$dateText = optional($article->updated_at)->format('F j, Y') ?? optional($article->created_at)->format('F j, Y');

$oldTitle = old('title', $article->title ?? '');
$oldContent = old('content', $article->content ?? '');
$oldCategory = old('category_id', $article->category_id ?? '');

$currentStatus = strtolower((string) old('status', $article->status ?? 'draft')); // draft|published

// ✅ Correct image field + correct URL
$imagePath = $article->featured_image ?? null; // e.g. "articles/abc.jpg"
$imageUrl = $imagePath ? Storage::url($imagePath) : null; // e.g. "/storage/articles/abc.jpg"
@endphp

<style>
    :root {
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

    .container,
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        max-width: 100% !important;
    }

    /* Page canvas */
    .edit-wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 26px 28px 34px;
        overflow: hidden;
        background: #fff;
    }

    /* Green side curves */
    .edit-wrap::before {
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

    .edit-wrap::after {
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

    .edit-inner {
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
        position: relative;
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

    .image-upload-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 10px 0 2px;
        flex-wrap: wrap;
    }

    .upload-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 22px;
        border-radius: 999px;
        background: var(--orange);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .2px;
        border: none;
        cursor: pointer;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
        white-space: nowrap;
    }

    .remove-btn {
        background: #d9534f;
        box-shadow: 0 10px 18px rgba(217, 83, 79, .25);
    }

    .file-input {
        display: none;
    }

    .file-name {
        display: inline-block;
        max-width: 320px;
        font-size: 14px;
        font-weight: 600;
        color: #3d3d3d;
        opacity: .85;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Status toggle pills */
    .status-pills {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-left: auto;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 22px;
        border-radius: 999px;
        background: var(--orange);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .2px;
        border: none;
        cursor: pointer;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
        white-space: nowrap;
    }

    .status-pill.active {
        background: var(--green);
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

    .field {
        margin-bottom: 12px;
    }

    .label {
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .2px;
        color: rgba(255, 255, 255, .95);
        margin: 0 0 8px;
    }

    .input,
    .textarea {
        width: 100%;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, .20);
        padding: 12px 14px;
        font-size: 16px;
        font-weight: 600;
        outline: none;
        background: rgba(255, 255, 255, .08);
        color: #fff;
    }

    .input::placeholder,
    .textarea::placeholder {
        color: rgba(255, 255, 255, .65);
        font-weight: 600;
    }

    .textarea {
        min-height: 220px;
        resize: none;
        line-height: 1.6;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 36px;
        padding: 0 28px;
        border-radius: 999px;
        background: var(--green);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .2px;
        border: none;
        cursor: pointer;
        box-shadow: 0 12px 22px rgba(0, 0, 0, .18);
        white-space: nowrap;
    }

    .cancel-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 36px;
        padding: 0 22px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
        font-weight: 900;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .2px;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, .18);
        white-space: nowrap;
    }

    @media (max-width: 900px) {
        .edit-inner {
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

        .actions {
            justify-content: center;
        }

        .file-name {
            max-width: 220px;
        }

        .status-pills {
            width: 100%;
            justify-content: flex-start;
            margin-left: 0;
        }
    }
</style>

<div class="edit-wrap">
    <div class="edit-inner">

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
            {{-- Image preview --}}
            <div class="image-area" id="imagePreviewBox">
                @if($imageUrl)
                <img id="imagePreview" src="{{ $imageUrl }}" alt="Post image">
                <div class="image-placeholder" id="imagePlaceholder" style="display:none;">No image uploaded for this post.</div>
                @else
                <div class="image-placeholder" id="imagePlaceholder">No image uploaded for this post.</div>
                <img id="imagePreview" src="" alt="Preview" style="display:none;">
                @endif
            </div>

            <form method="POST" action="{{ route('user.contents.update', $article->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Status controlled by pills --}}
                <input type="hidden" name="status" id="statusInput" value="{{ $currentStatus }}">

                {{-- Signals backend to remove existing image --}}
                <input type="hidden" name="remove_image" id="removeImageInput" value="0">

                <div class="image-upload-row">
                    <label class="upload-btn" for="imageInputEdit" style="cursor:pointer;">UPLOAD IMAGE</label>

                    {{-- ✅ Must match controller validation: featured_image --}}
                    <input id="imageInputEdit" class="file-input" type="file" name="featured_image" accept="image/*" />

                    <span id="imageNameEdit" class="file-name" aria-live="polite">
                        {{ $imagePath ? basename($imagePath) : 'No file chosen' }}
                    </span>

                    <button
                        type="button"
                        id="removeImageBtn"
                        class="upload-btn remove-btn"
                        @if(!$imageUrl) style="display:none;" @endif>
                        REMOVE IMAGE
                    </button>

                    {{-- Status toggle: pick Draft or Published --}}
                    <div class="status-pills" role="group" aria-label="Status">
                        <button type="button"
                            class="status-pill {{ $currentStatus === 'draft' ? 'active' : '' }}"
                            data-status="draft"
                            id="statusDraftBtn">
                            DRAFT
                        </button>

                        <button type="button"
                            class="status-pill {{ $currentStatus === 'published' ? 'active' : '' }}"
                            data-status="published"
                            id="statusPublishedBtn">
                            PUBLISHED
                        </button>
                    </div>
                </div>

                <div class="content-card">
                    <div class="field">
                        <div class="label">TITLE</div>
                        <input class="input" type="text" name="title" value="{{ $oldTitle }}" placeholder="Enter post title..." required>
                    </div>

                    {{-- Keep category ID (wire dropdown here if you want same as create page) --}}
                    <input type="hidden" name="category_id" value="{{ $oldCategory }}">

                    <div class="field">
                        <div class="label">CONTENT</div>
                        <textarea class="textarea" name="content" placeholder="Write your content here...." required>{{ $oldContent }}</textarea>
                    </div>

                    <div class="actions">
                        <a class="cancel-link" href="{{ route('user.contents.index') }}">CANCEL</a>
                        <button class="save-btn" type="submit">SAVE</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    (function() {
        // ===== Upload image filename + preview + remove =====
        const input = document.getElementById('imageInputEdit');
        const nameEl = document.getElementById('imageNameEdit');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');
        const removeBtn = document.getElementById('removeImageBtn');
        const removeInput = document.getElementById('removeImageInput');

        // If the article already has an image, show remove button
        if (preview && preview.getAttribute('src') && preview.getAttribute('src').trim() !== '') {
            if (removeBtn) removeBtn.style.display = 'inline-flex';
        }

        function setPlaceholderVisible(visible) {
            if (!placeholder) return;
            placeholder.style.display = visible ? 'flex' : 'none';
        }

        function setPreviewVisible(visible) {
            if (!preview) return;
            preview.style.display = visible ? 'block' : 'none';
        }

        function resetImageUI(markForRemoval) {
            if (input) input.value = '';
            if (nameEl) nameEl.textContent = 'No file chosen';

            // Clear preview (UI only). Backend removal is via remove_image=1.
            if (preview) preview.src = '';
            setPreviewVisible(false);
            setPlaceholderVisible(true);

            if (removeInput) removeInput.value = markForRemoval ? '1' : '0';
            if (removeBtn) removeBtn.style.display = 'none';
        }

        if (input) {
            input.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    if (nameEl) nameEl.textContent = file.name;

                    const url = URL.createObjectURL(file);
                    if (preview) preview.src = url;

                    setPreviewVisible(true);
                    setPlaceholderVisible(false);

                    if (removeInput) removeInput.value = '0'; // replacing, not removing
                    if (removeBtn) removeBtn.style.display = 'inline-flex';
                } else {
                    // if they cancel file dialog, keep current state (don't wipe existing)
                    // so do nothing here
                }
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                // Mark for removal (works even if there was an existing saved image)
                resetImageUI(true);
            });
        }

        // ===== Status toggle (Draft / Published) =====
        const statusInput = document.getElementById('statusInput');
        const draftBtn = document.getElementById('statusDraftBtn');
        const publishedBtn = document.getElementById('statusPublishedBtn');

        function setActive(btn, isActive) {
            if (!btn) return;
            btn.classList.toggle('active', isActive);
        }

        function setStatus(val) {
            if (!statusInput) return;
            statusInput.value = val;

            setActive(draftBtn, val === 'draft');
            setActive(publishedBtn, val === 'published');
        }

        [draftBtn, publishedBtn].forEach(btn => {
            if (!btn) return;
            btn.addEventListener('click', function() {
                setStatus(this.dataset.status);
            });
        });
    })();
</script>
@endsection