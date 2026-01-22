{{--
|--------------------------------------------------------------------------
| USER NAVIGATION
|--------------------------------------------------------------------------
| Navigation for authenticated users.
| Shows dashboard, content management, and profile.
|--------------------------------------------------------------------------
--}}

<link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root{
        --postit-green:#0B7A0B;
        --postit-purple:#1E0F52;
        --postit-orange:#E05A1B;
    }

    .user-nav{
        font-family:"Albert Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        height: 80px;                
        padding: 0 48px;             
        border-bottom: 1px solid #eee;
        background:#fff;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 16px;
        position: relative;
        z-index: 1000;
    }

    /* Brand (bigger like landing) */
    .user-nav .brand{
        display:flex;
        align-items:center;
        gap: 12px;
        text-decoration:none;
        color: var(--postit-green);
        font-weight: 900;
        letter-spacing: .6px;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .user-nav .brand img{
        height: 60px;     
        width: auto;
        display:block;
    }
    .user-nav .brand span{
        font-size: 16px;
        line-height: 1;
    }

    /* Center links */
    .user-nav .links{
        display:flex;
        align-items:center;
        gap: 44px;        
        font-weight: 800;
    }
    .user-nav .links a{
        text-decoration:none;
        color: var(--postit-purple);
        font-size: 20px;   
        transition: color .15s ease, transform .15s ease;
    }
    .user-nav .links a:hover{
        color: var(--postit-green);
        transform: translateY(-1px);
    }
    .user-nav .links a.active{
        color: var(--postit-green);
    }

    /* Right profile icon */
    .user-nav .right{
        display:flex;
        align-items:center;
        gap: 14px;
        white-space: nowrap;
    }
    .profile-pill{
        width:38px;
        height:38px;
        border-radius:999px;
        border:2px solid var(--postit-green);
        display:flex;
        align-items:center;
        justify-content:center;
        color: var(--postit-green);
        background:#fff;
        text-decoration:none;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .profile-pill:hover{
        transform: translateY(-1px);
        box-shadow: 0 10px 18px rgba(0,0,0,.10);
    }

    /* Responsive */
    @media (max-width: 768px){
        .user-nav{
            padding: 0 18px;
            height: 72px;
        }
        .user-nav .brand img{ height: 52px; }
        .user-nav .links{ display:none; }
    }
</style>

<nav class="user-nav">
    {{-- Left: Brand --}}
    <a class="brand" href="{{ route('user.dashboard') }}">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!">
    </a>

    {{-- Center: Main links --}}
    <div class="links">
        <a href="{{ route('user.dashboard') }}"
           class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('user.contents.index') }}"
           class="{{ request()->routeIs('user.contents.*') ? 'active' : '' }}">
            Contents
        </a>

        <a href="{{ route('user.contents.create') }}"
           class="{{ request()->routeIs('user.contents.create') ? 'active' : '' }}">
            New Post
        </a>
    </div>

    {{-- Right: Profile --}}
    <div class="right">
        <a class="profile-pill"
           href="{{ route('user.profile.edit') }}"
           title="Profile"
           aria-label="Profile">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 21a8 8 0 1 0-16 0"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                      stroke="currentColor" stroke-width="2"/>
            </svg>
        </a>
    </div>
</nav>
