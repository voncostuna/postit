@extends('layouts.app')

@section('content')
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
        overflow-x: hidden;
    }

    /* Page canvas */
    .wrap {
        position: relative;
        min-height: 100vh;
        width: 100%;
        padding: 24px 32px 32px;
        overflow: hidden;
        background: #fff;
    }

    .wrap::before {
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

    .wrap::after {
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

    .inner {
        position: relative;
        z-index: 1;
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
        padding-left: 6px;
        margin-left: 100px;
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

    .back-btn {
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
        text-decoration: none;
        text-transform: uppercase;
        box-shadow: 0 10px 20px rgba(0, 0, 0, .18);
        margin-top: 6px;
        white-space: nowrap;
        line-height: 1;
        min-width: 110px;
        flex: 0 0 auto;
    }

    /* Errors */
    .errors {
        margin: 14px auto 0;
        width: min(1100px, 100%);
        background: #fff0f0;
        border: 1px solid #ffcccc;
        border-radius: 12px;
        padding: 12px 14px;
        font-weight: 800;
        color: #842029;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .10);
    }

    /* Card shell */
    .shell {
        margin: 22px auto 0;
        width: min(1100px, 100%);
        background: linear-gradient(180deg, #20055f 0%, #15024b 100%);
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 22px 40px rgba(0, 0, 0, .22);
    }

    .card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 16px 20px;
        border: 1px solid rgba(0, 0, 0, .06);
    }

    .grid2 {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 16px;
        align-items: start;
    }

    .field {
        min-width: 0;
    }

    label {
        display: block;
        font-weight: 900;
        color: #111;
        margin-bottom: 8px;
        font-size: 15px;
    }

    input,
    textarea {
        width: 100%;
        max-width: 100%;
        display: block;
        border: 1px solid rgba(0, 0, 0, .18);
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 15px;
        font-weight: 700;
        outline: none;
        background: #fff;
    }

    textarea {
        min-height: 320px;
        resize: vertical;
        font-weight: 600;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    .hint {
        font-size: 12px;
        font-weight: 700;
        color: #666;
        margin-top: 8px;
        line-height: 1.3;
    }

    .toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 14px 0 10px;
        align-items: center;
        justify-content: space-between;
    }

    .tpl-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pill {
        height: 34px;
        padding: 0 18px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        color: #fff;
        font-weight: 900;
        text-transform: uppercase;
        box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
        line-height: 1;
        white-space: nowrap;
    }

    .pill-orange {
        background: var(--orange);
    }

    .pill-green {
        background: var(--green);
    }

    .pill-purple {
        background: var(--purple-3);
    }

    .editor-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 16px;
        margin-top: 10px;
        align-items: start;
    }

    .preview {
        border: 1px solid rgba(0, 0, 0, .18);
        border-radius: 12px;
        padding: 14px;
        min-height: 320px;
        background: #fff;
        overflow: auto;
    }

    .actions {
        display: flex;
        gap: 10px;
        margin-top: 14px;
        justify-content: flex-end;
        flex-wrap: wrap;
        align-items: center;
    }

    @media(max-width: 980px) {
        .wrap {
            padding: 18px 18px 26px;
        }

        .shell {
            padding: 18px;
        }
    }

    @media(max-width: 900px) {

        .grid2,
        .editor-grid {
            grid-template-columns: 1fr;
        }

        .title-block h1 {
            font-size: 34px;
        }

        .toolbar {
            align-items: flex-start;
        }
    }
</style>

<div class="wrap">
    <div class="inner">
        <div class="top-row">
            <div class="title-block">
                <h1>Create Page</h1>
                <p>Add a new static page/tab for the website.</p>
            </div>

            <a class="back-btn" href="{{ route('admin.pages.index') }}">Back</a>
        </div>

        @if ($errors->any())
        <div class="errors">
            Please fix the following:
            <ul style="margin:8px 0 0 18px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="shell">
            <div class="card">
                <form action="{{ route('admin.pages.store') }}" method="POST">
                    @csrf

                    <div class="grid2">
                        <div class="field">
                            <label>Title</label>
                            <input name="title" value="{{ old('title') }}" required autocomplete="off">
                        </div>

                        <div class="field">
                            <label>Slug (optional)</label>
                            <input name="slug" value="{{ old('slug') }}" placeholder="privacy-policy" autocomplete="off">
                            <div class="hint">Leave blank to auto-generate from title.</div>
                        </div>
                    </div>

                    <div class="toolbar">
                        <div class="tpl-actions">
                            <button type="button" class="pill pill-purple" id="insertAboutTpl">Insert ABOUT Template</button>
                            <button type="button" class="pill pill-purple" id="insertContactTpl">Insert CONTACT Template</button>
                        </div>

                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <button type="button" class="pill pill-orange" id="clearEditor">Clear</button>
                            <button type="button" class="pill pill-green" id="togglePreview">Toggle Preview</button>
                        </div>
                    </div>

                    <div class="editor-grid">
                        <div class="field">
                            <label>Content (HTML allowed)</label>
                            <textarea id="contentEditor" name="content" placeholder="Type your page content...">{{ old('content') }}</textarea>
                            <div class="hint">
                                Tip: Keep your landing page classes (<code>about-grid</code>, <code>contact-title</code>, etc.) to preserve styling.
                            </div>
                        </div>

                        <div class="field" id="previewWrap">
                            <label>Live Preview</label>
                            <div class="preview" id="livePreview"></div>
                            <div class="hint">
                                This preview renders HTML exactly as it will appear (inside your landing page styling).
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="pill pill-orange" type="submit" name="status" value="draft">Save Draft</button>
                        <button class="pill pill-green" type="submit" name="status" value="published">Publish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const editor = document.getElementById('contentEditor');
        const preview = document.getElementById('livePreview');
        const previewWrap = document.getElementById('previewWrap');
        const toggleBtn = document.getElementById('togglePreview');

        const aboutTpl = `<div class="about-headings">
    <div class="about-title">ABOUT US</div>
    <div class="about-title right">OUR MISSION</div>
</div>

<div class="about-grid">
    <div class="about-col">
        <p>
            Post It! is a content management platform designed to make creating,
            organizing, and publishing digital content simple and efficient.
        </p>

        <p>
            Built with usability and organization in mind, Post It! supports different
            user roles to ensure that content is managed responsibly.
        </p>

        <p>
            Our goal is to provide a clean and reliable platform that helps individuals and
            teams focus on what matters most — sharing ideas, information, and stories.
        </p>
    </div>

    <div class="about-col">
        <p>
            At Post It!, our mission is to provide a simple and welcoming space where people
            can create, share, and manage meaningful content with ease.
        </p>

        <div class="about-subhead">OUR VISION</div>

        <p>
            Our vision is to become a trusted digital space where creativity and communication thrive.
        </p>
    </div>
</div>`;

        const contactTpl = `<h2 class="contact-title">get in touch!</h2>

<div class="contact-subtext">
    We’d love to hear from you! If you have questions, feedback, or need assistance,
    feel free to reach out using the details below.
</div>

<div class="contact-details">
    <div><b>Email:</b> support@postit.com</div>
    <div><b>Phone:</b> +63 912 345 6789</div>

    <div class="contact-hours-title"><b>Office Hours:</b></div>
    <div>Monday – Friday</div>
    <div>9:00 AM – 5:00 PM</div>
</div>

<div class="contact-footer">
    Post <span class="orange">Smarter.</span>
    Manage <span class="navy">Better.</span>
    Publish <span class="green">Faster.</span>
</div>`;

        function refreshPreview() {
            preview.innerHTML = editor.value || '<div style="color:#666; font-weight:800;">Nothing to preview yet.</div>';
        }

        // Initial preview
        refreshPreview();

        // Live update (debounced)
        let t;
        editor.addEventListener('input', () => {
            clearTimeout(t);
            t = setTimeout(refreshPreview, 120);
        });

        document.getElementById('insertAboutTpl').addEventListener('click', () => {
            editor.value = aboutTpl;
            refreshPreview();
            editor.focus();
        });

        document.getElementById('insertContactTpl').addEventListener('click', () => {
            editor.value = contactTpl;
            refreshPreview();
            editor.focus();
        });

        document.getElementById('clearEditor').addEventListener('click', () => {
            if (confirm('Clear the editor content?')) {
                editor.value = '';
                refreshPreview();
                editor.focus();
            }
        });

        toggleBtn.addEventListener('click', () => {
            const hidden = previewWrap.style.display === 'none';
            previewWrap.style.display = hidden ? '' : 'none';
            toggleBtn.textContent = hidden ? 'Hide Preview' : 'Show Preview';
        });
    })();
</script>
@endsection