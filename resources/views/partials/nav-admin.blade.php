{{--
|--------------------------------------------------------------------------
| ADMIN NAVIGATION
|--------------------------------------------------------------------------
| Navigation for administrators.
| Provides access to system-wide management modules.
|--------------------------------------------------------------------------
--}}

<link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --postit-green: #0B7A0B;
        --postit-purple: #1E0F52;
    }

    .admin-nav {
        font-family: "Albert Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        height: 80px;
        padding: 0 48px;
        border-bottom: 1px solid #eee;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    /* Brand */
    .admin-nav .brand img {
        height: 60px;
        width: auto;
        display: block;
    }

    /* Center links */
    .admin-nav .links {
        display: flex;
        align-items: center;
        gap: 44px;
        font-weight: 800;
    }

    .admin-nav .links a {
        text-decoration: none;
        color: var(--postit-purple);
        font-size: 20px;
        transition: color .2s ease;
    }

    .admin-nav .links a.active,
    .admin-nav .links a:hover {
        color: var(--postit-green);
    }

    /* Right profile icon */
    .admin-nav .right {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .profile-pill {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        border: 2px solid var(--postit-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--postit-purple);
        background: #fff;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .profile-pill.active,
    .profile-pill:hover {
        background: rgba(30, 15, 82, .05);
        border-color: var(--postit-green);
        color: var(--postit-green);
    }

    @media (max-width: 900px) {
        .admin-nav {
            padding: 0 24px;
        }

        .admin-nav .links {
            gap: 26px;
        }
    }

    @media (max-width: 768px) {
        .admin-nav .links {
            display: none;
        }
    }
</style>

<nav class="admin-nav">
    {{-- Left: Brand --}}
    <div class="brand">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!">
        </a>
    </div>

    {{-- Center: Admin links --}}
    <div class="links">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('admin.contents.index') }}"
           class="{{ request()->routeIs('admin.contents.*') ? 'active' : '' }}">
            Contents
        </a>

        <a href="{{ route('admin.contents.create') }}"
           class="{{ request()->routeIs('admin.contents.create') ? 'active' : '' }}">
            New Post
        </a>

        <a href="{{ route('admin.pages.index') }}"
           class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
            Pages
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            User Management
        </a>
    </div>

    {{-- Right: Admin profile --}}
    <div class="right">
        <a class="profile-pill {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
           href="{{ route('admin.profile.edit') }}"
           title="My Profile">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M20 21a8 8 0 1 0-16 0"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <path d="M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                      stroke="currentColor" stroke-width="2" />
            </svg>
        </a>
    </div>
</nav>
