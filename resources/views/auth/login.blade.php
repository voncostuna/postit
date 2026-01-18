<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post It! | Login</title>

    <link rel="icon" href="{{ asset('assets/icons/mainLogo.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/icons/mainLogo.ico') }}" type="image/x-icon">

    <style>
        :root {
            --green: #00924A;
            --navy: #1C2338;
            --purple: #21085B;
            --orange: #E85F1A;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }

        body {
            /* warm orange background like screenshot */
            background: linear-gradient(180deg, #FFE9DE 0%, #F6A47E 45%, #E85F1A 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 540px;
            background: #fff;
            border-radius: 6px;
            padding: 34px 70px 32px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, .12);
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 18px;
        }

        .logo-wrap img {
            height: 80px;
            width: auto;
            display: block;
        }

        .tagline {
            margin-top: 6px;
            font-size: 16px;
            color: #666;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #222;
            margin: 14px 0 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 34px;
            border: 1.6px solid #777;
            border-radius: 6px;
            padding: 0 12px;
            outline: none;
            font-size: 14px;
            background: #fff;
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 12px;
            color: #333;
        }

        .check-row input {
            transform: scale(.9);
        }

        .btn {
            width: 100%;
            height: 34px;
            margin-top: 14px;
            background: var(--purple);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            transition: opacity .2s ease;
        }

        .btn:hover {
            opacity: .88;
        }

        .bottom {
            margin-top: 14px;
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .bottom a {
            color: var(--purple);
            font-weight: 800;
            text-decoration: none;
            margin-left: 6px;
            transition: opacity .2s ease;
        }

        .bottom a:hover {
            opacity: .88;
        }

        /* small screens */
        @media (max-width: 620px) {
            .card {
                width: min(540px, 92vw);
                padding: 30px 26px 28px;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo-wrap">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!">
            <div class="tagline">Share your IDEAS TODAY!</div>
        </div>

        <form method="POST" action="{{ route('login.perform') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>

            <div class="check-row">
                <input id="admin" name="is_admin" type="checkbox" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                <label for="admin" style="margin:0;font-weight:600;">Admin</label>
            </div>

            @if ($errors->any())
            <div style="margin-top:10px;font-size:12px;color:#c00;">
                {{ $errors->first() }}
            </div>
            @endif

            <button type="submit" class="btn">Login</button>

            <div class="bottom">
                Don’t have an account?
                <a href="{{ route('register') }}">Register</a>
            </div>
        </form>
    </div>
</body>

</html>