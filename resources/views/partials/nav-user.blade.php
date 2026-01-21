{{--
|--------------------------------------------------------------------------
| USER NAVIGATION
|--------------------------------------------------------------------------
| Navigation for authenticated users.
| Shows dashboard, content management, profile, and logout.
|
| TODO:
| - Add active state styling
| - Add more tabs if applicable (based on UI)
|--------------------------------------------------------------------------
--}}
<nav style="padding: 12px 18px; border-bottom: 1px solid #eee; display:flex; justify-content:space-between;">
    <div>
        <a href="{{ route('user.dashboard') }}">Dashboard</a>
        <a href="{{ route('user.contents.index') }}">Contents</a>
        <a href="{{ route('user.activity-logs.index') }}">Activity Logs</a>
        <a href="{{ route('user.profile.edit') }}">Profile</a>
    </div>

    <div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</nav>