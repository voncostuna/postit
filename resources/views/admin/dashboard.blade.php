<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Dashboard | Post It!</title>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }} (Role: {{ auth()->user()->role }})</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>

</html>