{{--
|--------------------------------------------------------------------------
| ADMIN NAVIGATION
|--------------------------------------------------------------------------
| Navigation for administrators.
| Provides access to system-wide management modules.
|
| TODO:
| - Group links into dropdowns if menu grows
| - Add more tabs if applicable (based on UI)
|--------------------------------------------------------------------------
--}}
<nav style="padding: 12px 18px; border-bottom: 1px solid #eee; display:flex; justify-content:space-between;">
    <div>
        <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        <a href="{{ route('admin.users.index') }}">Users</a>
        <a href="{{ route('admin.contents.index') }}">Contents</a>
        <a href="{{ route('admin.pages.index') }}">Pages</a>
        <a href="{{ route('admin.activity-logs.index') }}">Activity Logs</a>
    </div>

    <div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</nav>