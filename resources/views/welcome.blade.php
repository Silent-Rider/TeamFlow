<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'MyApp') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/css/pages/welcome.css', 'resources/js/app.js'])
    </head>

    <body>
        <nav class="nav">
            <a href="/" class="nav-logo">
                <img src="{{ asset('images/teamflow_logo.svg') }}" alt="logo" width="28" height="28">
                {{ config('app.name', 'MyApp') }}<span>.</span>
            </a>
        </nav>

        <section class="hero">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>

            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                {{ __('welcome.badge') }}
            </div>

            <h1 class="hero-title">
                {{ __('welcome.hero_title_1') }}<br>
                <span class="accent">{{ __('welcome.hero_title_2') }}</span>
                {{ __('welcome.hero_title_3') }}
            </h1>

            <p class="hero-sub">{{ __('welcome.hero_sub') }}</p>

            <div class="hero-cta">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">{{ __('welcome.btn_register') }}</a>
                @endif
                <a href="{{ route('login') }}" class="btn-secondary">{{ __('welcome.btn_login') }}</a>
            </div>
        </section>

        <section class="features">
            <p class="section-label">{{ __('welcome.features_label') }}</p>
            <h2 class="section-title">{{ __('welcome.features_title') }}</h2>

            <div class="cards-grid">
                <div class="card">
                    <div class="card-icon">🚀</div>
                    <div class="card-title">{{ __('welcome.card1_title') }}</div>
                    <p class="card-text">{{ __('welcome.card1_text') }}</p>
                </div>
                <div class="card">
                    <div class="card-icon">👥️</div>
                    <div class="card-title">{{ __('welcome.card2_title') }}</div>
                    <p class="card-text">{{ __('welcome.card2_text') }}</p>
                </div>
                <div class="card">
                    <div class="card-icon">🎨</div>
                    <div class="card-title">{{ __('welcome.card3_title') }}</div>
                    <p class="card-text">{{ __('welcome.card3_text') }}</p>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <h2 class="cta-title">{{ __('welcome.cta_title') }}</h2>
            <p class="cta-sub">{{ __('welcome.cta_sub') }}</p>
            @guest
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">{{ __('welcome.btn_signup') }}</a>
                @endif
            @endguest
        </section>

        <footer>
            <a href="/" class="nav-logo">
                <img src="{{ asset('images/teamflow_logo.svg') }}" alt="logo" width="28" height="28">
                {{ config('app.name', 'MyApp') }}<span>.</span>
            </a>
            <span>{{ date('Y') }} · {{ __('welcome.footer_rights') }}</span>
        </footer>

    </body>
</html>
