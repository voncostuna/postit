{{--
|--------------------------------------------------------------------------
| MAIN APPLICATION LAYOUT
|--------------------------------------------------------------------------
| Base layout for authenticated pages (User & Admin).
| Includes role-based navigation and a content section.
|-------------------------------------------------------------------------- 
--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post It!</title>

    <link rel="icon" href="{{ asset('assets/icons/mainLogo.ico') }}" type="image/x-icon">

    {{-- Use Albert Sans globally --}}
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root{
            --postit-green:#0B7A0B;
            --postit-purple:#1E0F52;
            --postit-orange:#E05A1B;

            --ok:#0B7A0B;
            --err:#B91C1C;
            --warn:#B45309;
            --info:#1E0F52;
        }

        body{
            margin:0;
            font-family:"Albert Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:#fff;
            color:#111;
        }

        /* ---------- TOAST ALERTS ---------- */
        .toast-stack{
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: min(420px, calc(100vw - 32px));
            pointer-events: none; /* only the toast itself is clickable */
        }

        .toast{
            pointer-events: auto;
            display: grid;
            grid-template-columns: 32px 1fr 32px;
            gap: 10px;
            align-items: start;

            padding: 14px 14px;
            border-radius: 14px;
            font-weight: 700;
            border: 1px solid rgba(0,0,0,.08);
            box-shadow: 0 18px 40px rgba(0,0,0,.18);
            background: #fff;
            overflow: hidden;
            transform: translateY(-6px);
            opacity: 0;
            animation: toast-in .18s ease forwards;
        }

        /* ✅ fade-out */
        .toast.is-hiding{
            opacity: 0 !important;
            transform: translateY(-6px) !important;
            transition: opacity .18s ease, transform .18s ease;
        }

        @keyframes toast-in{
            to { transform: translateY(0); opacity: 1; }
        }

        .toast .ico{
            width: 32px; height: 32px;
            border-radius: 10px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-weight: 900;
            margin-top: 2px;
            box-shadow: 0 10px 20px rgba(0,0,0,.10);
        }

        .toast .msg{
            font-weight: 750;
            line-height: 1.35;
            color:#111;
        }

        .toast .msg small{
            display:block;
            font-weight: 650;
            opacity:.85;
            margin-top: 4px;
        }

        .toast .close{
            width: 32px; height: 32px;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,.10);
            background: rgba(255,255,255,.6);
            cursor: pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#111;
        }

        .toast.success{ border-left: 6px solid var(--ok); }
        .toast.success .ico{ background: linear-gradient(180deg, #14A414, var(--ok)); }

        .toast.error{ border-left: 6px solid var(--err); }
        .toast.error .ico{ background: linear-gradient(180deg, #EF4444, var(--err)); }

        .toast.warning{ border-left: 6px solid var(--warn); }
        .toast.warning .ico{ background: linear-gradient(180deg, #F59E0B, var(--warn)); }

        .toast.info{ border-left: 6px solid var(--info); }
        .toast.info .ico{ background: linear-gradient(180deg, #3B2A86, var(--info)); }

        .toast ul{
            margin: 8px 0 0;
            padding-left: 18px;
            font-weight: 650;
        }
        .toast li{ margin: 4px 0; }

        /* ---------- CONFIRM MODAL (logout/delete) ---------- */
        .modal-backdrop{
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 9998;
            display:none;
            align-items:center;
            justify-content:center;
            padding: 18px;
        }
        .modal-backdrop.open{ display:flex; }

        .modal{
            width: min(520px, 100%);
            background:#fff;
            border-radius: 16px;
            box-shadow: 0 24px 70px rgba(0,0,0,.30);
            overflow:hidden;
            border: 1px solid rgba(0,0,0,.10);
            transform: translateY(8px);
            opacity: 0;
            animation: modal-in .16s ease forwards;
        }
        @keyframes modal-in{
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-head{
            padding: 16px 18px;
            display:flex;
            align-items:center;
            gap: 12px;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }

        .modal-badge{
            width: 40px; height: 40px;
            border-radius: 14px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            background: linear-gradient(180deg, #EF4444, #B91C1C);
            box-shadow: 0 14px 28px rgba(185,28,28,.25);
            flex: 0 0 auto;
        }

        .modal-title{
            font-weight: 900;
            font-size: 18px;
            color:#111;
            margin:0;
        }

        .modal-body{
            padding: 14px 18px 6px;
            color:#222;
            font-weight: 650;
            line-height: 1.45;
        }

        .modal-foot{
            padding: 14px 18px 18px;
            display:flex;
            gap: 10px;
            justify-content:flex-end;
        }

        .btn{
            border: none;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 850;
            cursor: pointer;
        }
        .btn-ghost{
            background: rgba(0,0,0,.06);
            color:#111;
        }
        .btn-danger{
            background: linear-gradient(180deg, #EF4444, #B91C1C);
            color:#fff;
            box-shadow: 0 14px 26px rgba(185,28,28,.22);
        }
        .btn-primary{
            background: linear-gradient(180deg, #14A414, var(--postit-green));
            color:#fff;
            box-shadow: 0 14px 26px rgba(11,122,11,.18);
        }

        /* keep your existing container behavior */
        .container{
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('partials.nav')

    {{-- Toasts (flash + validation) --}}
    <div class="toast-stack" id="toastStack">
        @if(session('success'))
            <div class="toast success" data-autohide="4500">
                <div class="ico">✓</div>
                <div class="msg">
                    {{ session('success') }}
                    <small>Action completed successfully.</small>
                </div>
                <button class="close" type="button" aria-label="Close">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="toast error" data-autohide="7000">
                <div class="ico">!</div>
                <div class="msg">
                    {{ session('error') }}
                    <small>Please try again.</small>
                </div>
                <button class="close" type="button" aria-label="Close">✕</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="toast warning" data-autohide="6500">
                <div class="ico">!</div>
                <div class="msg">
                    {{ session('warning') }}
                    <small>Review before continuing.</small>
                </div>
                <button class="close" type="button" aria-label="Close">✕</button>
            </div>
        @endif

        @if(session('info'))
            <div class="toast info" data-autohide="5500">
                <div class="ico">i</div>
                <div class="msg">
                    {{ session('info') }}
                </div>
                <button class="close" type="button" aria-label="Close">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div class="toast error" data-autohide="0">
                <div class="ico">!</div>
                <div class="msg">
                    Please fix the following:
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="close" type="button" aria-label="Close">✕</button>
            </div>
        @endif
    </div>

    {{-- ✅ Confirm Modal (used by logout/delete) --}}
    <div class="modal-backdrop" id="confirmModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
            <div class="modal-head">
                <div class="modal-badge" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M12 9v4" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 17h.01" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="modal-title" id="confirmTitle">Confirm action</p>
            </div>

            <div class="modal-body" id="confirmText">Are you sure?</div>

            <div class="modal-foot">
                <button class="btn btn-ghost" type="button" id="confirmCancel">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirmOk">Confirm</button>
            </div>
        </div>
    </div>

    <main class="container py-4">
        @yield('content')
    </main>

    {{-- ✅ Toast JS --}}
    <script>
        (function () {
            const stack = document.getElementById('toastStack');
            if (!stack) return;

            function hideToast(toast) {
                toast.classList.add('is-hiding');
                setTimeout(() => toast.remove(), 200);
            }

            stack.querySelectorAll('.toast .close').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const toast = btn.closest('.toast');
                    if (toast) hideToast(toast);
                });
            });

            stack.querySelectorAll('.toast[data-autohide]').forEach(toast => {
                const ms = parseInt(toast.getAttribute('data-autohide') || '0', 10);
                if (ms > 0) setTimeout(() => hideToast(toast), ms);
            });
        })();
    </script>

    {{-- ✅ Confirm Modal JS (logout/delete/any confirm form) --}}
    <script>
        (function () {
            const modal = document.getElementById('confirmModal');
            const cancelBtn = document.getElementById('confirmCancel');
            const okBtn = document.getElementById('confirmOk');
            const titleEl = document.getElementById('confirmTitle');
            const textEl = document.getElementById('confirmText');

            if (!modal || !cancelBtn || !okBtn || !titleEl || !textEl) return;

            let pendingForm = null;

            function openModal({ title, text, okText, danger }) {
                titleEl.textContent = title || 'Confirm action';
                textEl.textContent = text || 'Are you sure?';

                okBtn.textContent = okText || 'Confirm';

                // danger button for delete, green for logout/normal confirms
                okBtn.classList.toggle('btn-danger', !!danger);
                okBtn.classList.toggle('btn-primary', !danger);

                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingForm = null;
            }

            // click backdrop closes
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            // ESC closes
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
            });

            cancelBtn.addEventListener('click', closeModal);

            okBtn.addEventListener('click', () => {
                if (pendingForm) pendingForm.submit();
            });

            // Intercept forms with data-confirm
            document.addEventListener('submit', (e) => {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;

                const mode = form.dataset.confirm; // "logout" | "delete" | "confirm"
                if (!mode) return;

                e.preventDefault();
                pendingForm = form;

                // defaults
                let title = form.dataset.confirmTitle || 'Confirm action';
                let text  = form.dataset.confirmText  || 'Are you sure you want to continue?';
                let okText = form.dataset.confirmOk || 'Confirm';
                let danger = false;

                if (mode === 'logout') {
                    title = form.dataset.confirmTitle || 'Logout?';
                    text  = form.dataset.confirmText  || 'Are you sure you want to logout?';
                    okText = form.dataset.confirmOk || 'Logout';
                    danger = false;
                }

                if (mode === 'delete') {
                    title = form.dataset.confirmTitle || 'Delete?';
                    text  = form.dataset.confirmText  || 'This action cannot be undone.';
                    okText = form.dataset.confirmOk || 'Delete';
                    danger = true;
                }

                openModal({ title, text, okText, danger });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
