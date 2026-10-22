<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('school.name'))</title>
    <meta name="description" content="@yield('description', config('school.tagline'))">
    <meta property="og:title" content="@yield('title', config('school.name'))">
    <meta property="og:description" content="@yield('description', config('school.tagline'))">
    <meta property="og:image" content="{{ asset('images/hero-car.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=27">
</head>
<body>
    <div id="page-loader" role="status" aria-live="polite" aria-label="Loading">
        <div class="page-loader__brand" aria-hidden="true">
            <span class="page-loader__badge">L</span>
            <span class="page-loader__text">
                <strong>King's Cross</strong>
                <span>Driving School</span>
            </span>
        </div>
        <span class="page-loader__ring" aria-hidden="true"></span>
        <p class="page-loader__label">Loading…</p>
    </div>
    <header class="site-header">
        <div class="container site-header__inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand__badge" aria-hidden="true">L</span>
                <span class="brand__text">
                    <strong>King's Cross</strong>
                    <span>Driving School</span>
                </span>
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="site-menu" aria-label="Open menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav id="site-menu" class="site-nav" data-site-nav>
                <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Home</a>
                <a href="{{ route('lessons') }}" @class(['is-active' => request()->routeIs('lessons')])>Driving Lessons</a>
                <a href="{{ route('areas') }}" @class(['is-active' => request()->routeIs('areas')])>Areas</a>
                <a href="{{ route('gallery') }}" @class(['is-active' => request()->routeIs('gallery')])>Gallery</a>
                <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About Us</a>
                <a href="{{ route('contact') }}" @class(['is-active' => request()->routeIs('contact')])>Contact</a>
                <a class="btn btn--primary btn--nav" href="{{ route('book') }}">Book a Lesson</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container site-footer__grid">
            <div>
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand__badge" aria-hidden="true">L</span>
                    <span class="brand__text">
                        <strong>King's Cross</strong>
                        <span>Driving School</span>
                    </span>
                </a>
                <p>{{ config('school.name') }} offers friendly, professional driving lessons in London, with flexible times and local coverage around King's Cross.</p>
            </div>

            <div>
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('lessons') }}">Driving Lessons</a></li>
                    <li><a href="{{ route('areas') }}">Areas</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3>Contact Us</h3>
                <ul class="footer-contact">
                    <li>
                        <img src="{{ asset('icons/phone.svg') }}" alt="">
                        <a href="tel:{{ config('school.phone_tel') }}">{{ config('school.phone_display') }}</a>
                    </li>
                    <li>
                        <img src="{{ asset('icons/mail.svg') }}" alt="">
                        <a href="mailto:{{ config('school.email') }}">{{ config('school.email') }}</a>
                    </li>
                    <li>
                        <img src="{{ asset('icons/map-pin.svg') }}" alt="">
                        <span>{{ config('school.address') }}</span>
                    </li>
                </ul>
            </div>

            <div>
                <h3>Our Hours</h3>
                <ul class="footer-hours">
                    <li>{{ config('school.hours_label') }}</li>
                </ul>
            </div>
        </div>

        <div class="container site-footer__bottom">
            <p>© {{ date('Y') }} {{ config('school.name') }}.</p>
            <div class="site-footer__legal">
                <a href="{{ route('contact') }}">Privacy Policy</a>
                <a href="{{ route('contact') }}">Terms &amp; Conditions</a>
            </div>
        </div>
    </footer>
    <script>
        (function () {
            var loader = document.getElementById('page-loader');
            if (!loader) return;
            var started = Date.now();
            var hide = function () {
                var wait = Math.max(0, 500 - (Date.now() - started));
                setTimeout(function () { loader.classList.add('is-done'); }, wait);
            };
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hide);
            } else {
                hide();
            }
            setTimeout(function () { loader.classList.add('is-done'); }, 2500);
        })();
    </script>
    <script src="{{ asset('js/site.js') }}?v=3" defer></script>
</body>
</html>
