{{--
|--------------------------------------------------------------------------
| MAIN APPLICATION LAYOUT
|--------------------------------------------------------------------------
| Base layout for authenticated pages (User & Admin).
| Includes role-based navigation and a content section.
|
| TODO:
| - Add global styles/scripts
| - Improve layout styling if needed
|--------------------------------------------------------------------------
--}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Post It!') }}</title>

</head>

<body>
    @include('partials.nav')

    <main class="container py-4">
        @yield('content')
    </main>
</body>

</html>