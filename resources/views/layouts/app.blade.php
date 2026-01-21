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
    <title>Post It!</title>
    <link rel="icon" href="{{ asset('assets/icons/mainLogo.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>
    @include('partials.nav')

    <main class="container py-4">
        @yield('content')
    </main>
</body>

</html>