{{-- 
|--------------------------------------------------------------------------
| ROLE-BASED NAVIGATION SWITCH
|--------------------------------------------------------------------------
| Loads the correct navigation bar depending on:
| - Authenticated User
| - Admin
|
| TODO:
| - Adjust role checks if more roles are added
|--------------------------------------------------------------------------
--}}
@auth
    @if(auth()->user()->role === 'admin')
        @include('partials.nav-admin')
    @elseif(auth()->user()->role === 'user')
        @include('partials.nav-user')
    @else
        @include('partials.nav-guest')
    @endif
@endauth
