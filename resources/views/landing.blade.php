<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post It!</title>
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

        html {
            margin: 0;
            padding: 0;
            overflow-y: scroll;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #fff;
            color: #111;
            overflow: visible;
        }

        /* kill accidental nested scrollbars */
        body,
        section,
        header,
        div {
            overflow-y: visible;
        }

        /* NAV */
        .topbar {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            position: relative;
            z-index: 9999;
        }

        .ellipse,
        .contact-ellipse,
        .about-section::before,
        .about-section::after,
        .about-corners::before,
        .about-corners::after {
            pointer-events: none;
        }

        .brand img {
            height: 60px;
        }

        .nav {
            display: flex;
            gap: 44px;
            font-weight: 700;
        }

        .nav a {
            text-decoration: none;
            color: var(--navy);
            font-size: 16px;
            transition: color .2s ease;
        }

        .nav a.active {
            color: var(--green);
        }

        .nav a:hover {
            color: var(--green);
        }

        .login-btn {
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 7px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .2s ease;
        }

        .login-btn:hover {
            opacity: .85;
        }

        /* HERO */
        .hero {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            align-items: center;
            padding-right: 48px;
            padding-bottom: 40px;
        }

        /* LEFT SIDE */
        .left {
            position: relative;
            padding: 40px 0;
            overflow: visible;
        }

        .ellipse {
            position: absolute;
            left: 120px;
            top: 30px;
            transform: translateX(-120px);
            width: 650px;
            z-index: 0;
            pointer-events: none;
        }

        .hero-img {
            position: relative;
            width: 550px;
            margin-left: 40px;
            margin-top: 70px;
            z-index: 1;
        }

        /* RIGHT SIDE */
        .mini-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--green);
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 18px;
            font-size: 15px;
        }

        .mini-logo {
            height: 150px;
            width: auto;
            display: block;
            margin-bottom: -30px;
        }

        h1 {
            font-size: 52px;
            line-height: 1.08;
            margin: 0;
        }

        .orange {
            color: var(--orange);
            font-weight: 900;
        }

        .navy {
            color: var(--navy);
            font-weight: 900;
        }

        .green {
            color: var(--green);
            font-weight: 900;
        }

        .desc {
            margin-top: 18px;
            max-width: 460px;
            font-size: 16px;
            line-height: 1.65;
            color: #333;
        }

        .cta {
            margin-top: 28px;
        }

        .cta a {
            background: var(--purple);
            color: #fff;
            text-decoration: none;
            padding: 14px 52px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 18px;
            display: inline-block;
            cursor: pointer;
            transition: opacity .2s ease;
        }

        .cta a:hover {
            opacity: .85;
        }

        /* =========================
           ABOUT US SECTION
           ========================= */

        #about {
            scroll-margin-top: 90px;
        }

        .about-section {
            position: relative;
            padding: 90px 0 110px;
            background: #ffffff;
        }

        .about-section::before,
        .about-section::after {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            background: linear-gradient(180deg, #21085B 0%, #4B12D6 100%);
            z-index: 0;
        }

        .about-section::before {
            top: 0;
            left: 0;
            border-bottom-right-radius: 320px;
        }

        .about-section::after {
            top: 0;
            right: 0;
            border-bottom-left-radius: 320px;
        }

        .about-corners {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .about-corners::before,
        .about-corners::after {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            background: linear-gradient(180deg, #4B12D6 0%, #21085B 100%);
        }

        .about-corners::before {
            bottom: 0;
            left: 0;
            border-top-right-radius: 340px;
        }

        .about-corners::after {
            bottom: 0;
            right: 0;
            border-top-left-radius: 340px;
        }

        .about-card {
            position: relative;
            z-index: 1;
            max-width: 1080px;
            margin: 0 auto;
            background: #fff;
            border-radius: 220px;
            padding: 70px 90px 60px;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .03);
        }

        .about-top-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 26px;
        }

        .about-top-logo img {
            height: 100px;
            width: auto;
            display: block;
        }

        .about-headings {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: end;
            margin-bottom: 14px;
        }

        .about-title {
            text-align: center;
            font-weight: 900;
            letter-spacing: .5px;
            font-size: 20px;
            color: var(--orange);
        }

        .about-title.right {
            color: var(--navy);
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: start;
            margin-top: 18px;
        }

        .about-col p {
            margin: 0 0 18px;
            font-size: 16px;
            line-height: 1.65;
            color: #222;
            text-align: center;
        }

        .about-subhead {
            text-align: center;
            font-weight: 900;
            letter-spacing: .5px;
            color: var(--navy);
            margin: 26px 0 10px;
            font-size: 20px;
        }

        .about-cta {
            display: flex;
            justify-content: center;
            margin-top: 26px;
        }

        .about-cta .cta-btn {
            background: var(--purple);
            color: #fff;
            text-decoration: none;
            padding: 12px 58px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 16px;
            display: inline-block;
            cursor: pointer;
            transition: opacity .2s ease;
        }

        .about-cta .cta-btn:hover {
            opacity: .85;
        }

        /* =========================
           CONTACT SECTION
           ========================= */

        #contact {
            scroll-margin-top: 90px;
        }

        .contact-section {
            position: relative;
            padding: 70px 48px 80px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            align-items: center;
            gap: 40px;
        }

        .contact-left {
            position: relative;
            padding: 30px 0;
            overflow: visible;
        }

        .contact-ellipse {
            position: absolute;
            left: 100px;
            top: 20px;
            transform: translateX(-160px);
            width: 620px;
            z-index: 0;
            pointer-events: none;
        }

        .contact-img {
            position: relative;
            width: 540px;
            margin-left: 0;
            margin-top: 80px;
            z-index: 1;
        }

        .contact-right {
            padding-right: 10px;
        }

        .contact-top-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .contact-top-logo img {
            height: 60px;
            width: auto;
            display: block;
        }

        .contact-title {
            margin: 0;
            font-size: 64px;
            line-height: 1;
            font-weight: 900;
            color: var(--orange);
            margin-bottom: 30px;
            text-transform: lowercase;
        }

        .contact-subtext {
            margin-top: 10px;
            max-width: 520px;
            font-size: 16px;
            line-height: 1.65;
            color: #333;
        }

        .contact-details {
            margin-top: 26px;
            font-size: 16px;
            line-height: 1.6;
            color: #111;
        }

        .contact-details b {
            color: var(--purple);
        }

        .contact-hours-title {
            margin-top: 14px;
            font-weight: 900;
            color: var(--orange);
        }

        .contact-footer {
            margin-top: 44px;
            font-size: 18px;
            font-weight: 800;
            color: #111;
        }

        /* =========================
           FOOTER (NEW)
           ========================= */

        .site-footer {
            background: #0E8F01;
            padding: 11px 0;
            margin-top: 130px;
        }

        .site-footer p {
            margin: 0;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .2px;
        }

        /* MOBILE */
        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
                padding-right: 24px;
                padding-left: 24px;
            }

            .ellipse {
                left: 50%;
                transform: translateX(-50%);
                top: 20px;
            }

            .hero-img {
                margin: 0 auto;
                display: block;
                width: min(480px, 92vw);
            }

            .about-card {
                border-radius: 70px;
                padding: 55px 26px;
                margin: 0 18px;
            }

            .about-headings,
            .about-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .about-section::before,
            .about-section::after,
            .about-corners::before,
            .about-corners::after {
                width: 240px;
                height: 240px;
            }

            .contact-section {
                padding: 60px 24px 70px;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .contact-ellipse {
                left: 50%;
                transform: translateX(-50%);
                top: 10px;
                width: 640px;
            }

            .contact-img {
                margin: 0 auto;
                display: block;
                width: min(520px, 92vw);
            }

            .contact-title {
                font-size: 54px;
            }

            .site-footer p {
                font-size: 16px;
                padding: 0 16px;
            }
        }
    </style>
</head>

<body>

    <header class="topbar">
        <div class="brand">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!">
        </div>

        <nav class="nav">
            <a class="active" href="#">Home</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact</a>
        </nav>

        <a href="{{ route('login') }}" class="login-btn">Login</a>
    </header>

    <section class="hero">
        <div class="left">
            <img class="ellipse" src="{{ asset('assets/images/ellipse.svg') }}" alt="" aria-hidden="true">
            <img class="hero-img" src="{{ asset('assets/images/hero.svg') }}" alt="Hero illustration">
        </div>

        <div>
            <div class="mini-brand">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!" class="mini-logo">
            </div>

            <h1>
                Post <span class="orange">Smarter.</span><br>
                Manage <span class="navy">Better.</span><br>
                Publish <span class="green">Faster.</span>
            </h1>

            <div class="desc">
                Here in <span class="green"><b>Post It!</b></span>, users can create,
                organize, and manage content in one simple platform.
                <br><br>
                It allows easy publishing of articles while keeping everything
                structured, secure, and easy to use.
            </div>

            <div class="cta">
                <a href="#">Get Started</a>
            </div>
        </div>
    </section>

    <!-- ABOUT US SECTION -->
    <section id="about" class="about-section">
        <div class="about-corners" aria-hidden="true"></div>

        <div class="about-card">
            <div class="about-top-logo">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!">
            </div>

            <div class="about-headings">
                <div class="about-title">ABOUT US</div>
                <div class="about-title right">OUR MISSION</div>
            </div>

            <div class="about-grid">
                <div class="about-col">
                    <p>
                        Post It! is a content management platform designed to make creating,
                        organizing, and publishing digital content simple and efficient. The
                        system provides users with an intuitive space to write, edit, and manage
                        articles while keeping everything structured and easy to access.
                    </p>

                    <p>
                        Built with usability and organization in mind, Post It! supports different
                        user roles to ensure that content is managed responsibly. Administrators oversee
                        the platform and manage published content, while users are given the tools they
                        need to contribute and maintain their own articles.
                    </p>

                    <p>
                        Our goal is to provide a clean and reliable platform that helps individuals and
                        teams focus on what matters most — sharing ideas, information, and stories in a
                        clear and organized way. Whether for blogs, announcements, or informational content,
                        Post It! offers a simple solution for effective content publishing.
                    </p>
                </div>

                <div class="about-col">
                    <p>
                        At Post It!, our mission is to provide a simple and welcoming space where people
                        can create, share, and manage meaningful content with ease. We aim to empower users
                        to express ideas, tell stories, and organize information through a platform that values
                        clarity, creativity, and accessibility.
                    </p>

                    <div class="about-subhead">OUR VISION</div>

                    <p>
                        Our vision is to become a trusted digital space where creativity and communication thrive.
                        We envision Post It! as a platform that encourages thoughtful sharing, supports content creators
                        of all levels, and fosters a community built on collaboration, expression, and growth.
                    </p>
                </div>
            </div>

            <div class="about-cta">
                <a href="#" class="cta-btn">Get Started</a>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact" class="contact-section">
        <div class="contact-grid">
            <div class="contact-left">
                <img class="contact-ellipse" src="{{ asset('assets/images/contactEllipse.svg') }}" alt="" aria-hidden="true">
                <img class="contact-img" src="{{ asset('assets/images/contact.svg') }}" alt="Contact illustration">
            </div>

            <div class="contact-right">
                <div class="contact-top-logo">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="Post It!">
                </div>

                <h2 class="contact-title">get in touch!</h2>

                <div class="contact-subtext">
                    We’d love to hear from you! If you have questions, feedback, or need assistance,
                    feel free to reach out using the details below.
                </div>

                <div class="contact-details">
                    <div><b>Email:</b> support@postit.com</div>
                    <div><b>Phone:</b> +63 912 345 6789</div>

                    <div class="contact-hours-title"><b>Office Hours:</b></div>
                    <div>Monday – Friday</div>
                    <div>9:00 AM – 5:00 PM</div>
                </div>

                <div class="contact-footer">
                    Post <span class="orange">Smarter.</span>
                    Manage <span class="navy">Better.</span>
                    Publish <span class="green">Faster.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <p>© Post It. All rights reserved.</p>
    </footer>

</body>

</html>