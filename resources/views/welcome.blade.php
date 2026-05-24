<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'MyApp') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --clr-bg: #0d0d0f;
                --clr-surface: #16161a;
                --clr-accent: #c8f04a;
                --clr-accent2: #f07a2a;
                --clr-text: #f0ede8;
                --clr-muted: #6b6b72;
                --clr-border: #262630;
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            html { scroll-behavior: smooth; }

            body {
                background-color: var(--clr-bg);
                color: var(--clr-text);
                font-family: 'DM Sans', sans-serif;
                font-weight: 300;
                min-height: 100vh;
                overflow-x: hidden;
            }

            .nav {
                position: fixed;
                top: 0; left: 0; right: 0;
                z-index: 100;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1.25rem 2.5rem;
                backdrop-filter: blur(12px);
                background: rgba(13, 13, 15, 0.75);
                border-bottom: 1px solid var(--clr-border);
            }

            .nav-logo {
                font-family: 'Playfair Display', serif;
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                color: var(--clr-text);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .nav-logo span { color: var(--clr-accent); }

            .nav-links a {
                display: inline-block;
                padding: 0.45rem 1.2rem;
                font-size: 0.85rem;
                font-weight: 500;
                letter-spacing: 0.02em;
                border-radius: 6px;
                text-decoration: none;
                transition: background 0.2s, color 0.2s, border-color 0.2s;
            }

            .hero {
                position: relative;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 8rem 2rem 5rem;
                overflow: hidden;
            }

            .blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(120px);
                opacity: 0.18;
                pointer-events: none;
            }
            .blob-1 {
                width: 600px; height: 600px;
                background: var(--clr-accent);
                top: -150px; right: -100px;
                animation: drift1 12s ease-in-out infinite alternate;
            }
            .blob-2 {
                width: 500px; height: 500px;
                background: var(--clr-accent2);
                bottom: -100px; left: -80px;
                animation: drift2 15s ease-in-out infinite alternate;
            }
            .blob-3 {
                width: 300px; height: 300px;
                background: #f04a8a;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                animation: drift3 10s ease-in-out infinite alternate;
            }

            @keyframes drift1 { to { transform: translate(-40px, 60px) scale(1.1); } }
            @keyframes drift2 { to { transform: translate(50px, -40px) scale(1.05); } }
            @keyframes drift3 { to { transform: translate(-50%, -55%) scale(0.85); } }

            .hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(var(--clr-border) 1px, transparent 1px),
                    linear-gradient(90deg, var(--clr-border) 1px, transparent 1px);
                background-size: 60px 60px;
                opacity: 0.35;
                pointer-events: none;
            }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.35rem 1rem;
                border: 1px solid var(--clr-border);
                border-radius: 999px;
                font-size: 0.78rem;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--clr-muted);
                margin-bottom: 2rem;
                background: var(--clr-surface);
                animation: fadeUp 0.6s ease both;
            }

            .hero-badge-dot {
                width: 6px; height: 6px;
                border-radius: 50%;
                background: var(--clr-accent);
                animation: pulse 2s ease infinite;
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.4; transform: scale(0.7); }
            }

            .hero-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(3rem, 8vw, 7rem);
                font-weight: 900;
                line-height: 1.0;
                letter-spacing: -0.03em;
                max-width: 14ch;
                margin: 0 auto 1.5rem;
                animation: fadeUp 0.7s 0.1s ease both;
            }

            .hero-title .accent { color: var(--clr-accent); font-style: italic; }

            .hero-sub {
                font-size: clamp(1rem, 2vw, 1.2rem);
                color: var(--clr-muted);
                max-width: 48ch;
                line-height: 1.7;
                margin: 0 auto 2.5rem;
                animation: fadeUp 0.7s 0.2s ease both;
            }

            .hero-cta {
                display: flex;
                gap: 0.75rem;
                flex-wrap: wrap;
                justify-content: center;
                animation: fadeUp 0.7s 0.3s ease both;
            }

            .btn-primary {
                display: inline-block;
                padding: 0.85rem 2.25rem;
                background: var(--clr-accent);
                color: #0d0d0f;
                font-weight: 500;
                font-size: 0.95rem;
                border-radius: 8px;
                text-decoration: none;
                transition: transform 0.18s, box-shadow 0.18s;
                box-shadow: 0 0 0 0 rgba(200, 240, 74, 0.4);
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px rgba(200, 240, 74, 0.3);
            }

            .btn-secondary {
                display: inline-block;
                padding: 0.85rem 2.25rem;
                border: 1px solid var(--clr-border);
                color: var(--clr-text);
                font-weight: 400;
                font-size: 0.95rem;
                border-radius: 8px;
                text-decoration: none;
                background: var(--clr-surface);
                transition: border-color 0.18s, background 0.18s;
            }
            .btn-secondary:hover {
                border-color: var(--clr-muted);
                background: #1e1e24;
            }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ─── FEATURES ─── */
            .features {
                padding: 6rem 2rem;
                max-width: 1100px;
                margin: 0 auto;
            }

            .section-label {
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.12em;
                color: var(--clr-accent);
                margin-bottom: 1rem;
            }

            .section-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2rem, 4vw, 3rem);
                font-weight: 700;
                letter-spacing: -0.02em;
                max-width: 20ch;
                line-height: 1.15;
                margin-bottom: 4rem;
            }

            .cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1px;
                background: var(--clr-border);
                border: 1px solid var(--clr-border);
                border-radius: 12px;
                overflow: hidden;
            }

            .card {
                background: var(--clr-surface);
                padding: 2.5rem 2rem;
                transition: background 0.2s;
            }
            .card:hover { background: #1c1c22; }

            .card-icon {
                width: 42px; height: 42px;
                border-radius: 10px;
                background: rgba(200, 240, 74, 0.08);
                border: 1px solid rgba(200, 240, 74, 0.15);
                display: flex; align-items: center; justify-content: center;
                margin-bottom: 1.5rem;
                font-size: 1.2rem;
            }

            .card-title {
                font-family: 'Playfair Display', serif;
                font-size: 1.2rem;
                font-weight: 700;
                margin-bottom: 0.75rem;
            }

            .card-text {
                font-size: 0.9rem;
                color: var(--clr-muted);
                line-height: 1.7;
            }

            .cta-section {
                padding: 6rem 2rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .cta-section::before {
                content: '';
                position: absolute;
                width: 600px; height: 600px;
                background: radial-gradient(circle, rgba(200,240,74,0.08) 0%, transparent 70%);
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                pointer-events: none;
            }

            .cta-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2.2rem, 5vw, 4rem);
                font-weight: 900;
                letter-spacing: -0.03em;
                margin-bottom: 1.5rem;
            }

            .cta-sub {
                font-size: 1rem;
                color: var(--clr-muted);
                max-width: 42ch;
                margin: 0 auto 2.5rem;
                line-height: 1.7;
            }

            /* ─── FOOTER ─── */
            footer {
                border-top: 1px solid var(--clr-border);
                padding: 2rem 2.5rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: var(--clr-muted);
                font-size: 0.82rem;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .footer-logo {
                font-family: 'Playfair Display', serif;
                font-weight: 700;
                color: var(--clr-text);
                text-decoration: none;
                font-size: 1rem;
            }

            .footer-logo span { color: var(--clr-accent); }

            /* ─── RESPONSIVE ─── */
            @media (max-width: 640px) {
                .nav { padding: 1rem 1.25rem; }
                footer { justify-content: center; text-align: center; }
            }
        </style>
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
                Добро пожаловать
            </div>

            <h1 class="hero-title">
                Ваш проект.<br>
                <span class="accent">Гибче.</span> Быстрее.
            </h1>

            <p class="hero-sub">
                Современная платформа для управления задачами, командной работы
                и отслеживания прогресса — всё в одном месте.
            </p>

            <div class="hero-cta">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Начать бесплатно →</a>
                @endif
                <a href="{{ route('login') }}" class="btn-secondary">Войти в аккаунт</a>
            </div>
        </section>

        <section class="features">
            <p class="section-label">Почему TeamFlow?</p>
            <h2 class="section-title">Всё необходимое для управления проектами</h2>

            <div class="cards-grid">
                <div class="card">
                    <div class="card-icon">🚀</div>
                    <div class="card-title">Быстрый старт</div>
                    <p class="card-text">
                        Забудьте о долгих внедрениях. Создайте проект,
                        пригласите команду и начните работу за 5 минут. Никаких сложных инструкций.
                    </p>
                </div>
                <div class="card">
                    <div class="card-icon">👥️</div>
                    <div class="card-title">Полный контроль</div>
                    <p class="card-text">
                        Гибкая система ролей и прав доступа. Вы всегда знаете,
                        кто над чем работает, и видите статус каждой задачи в реальном времени.
                    </p>
                </div>
                <div class="card">
                    <div class="card-icon">🎨</div>
                    <div class="card-title">Удобный интерфейс</div>
                    <p class="card-text">
                        Чистый дизайн, интуитивная навигация. Тёмная тема
                        для комфортной работы в любое время суток.
                    </p>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <h2 class="cta-title">Готов начать?</h2>
            <p class="cta-sub">
                Создайте аккаунт за минуту и получите доступ
                ко всем возможностям платформы.
            </p>
            @guest
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Зарегистрироваться →</a>
                @endif
            @endguest
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Перейти в Dashboard →</a>
            @endauth
        </section>

        <footer>
            <a href="/" class="nav-logo">
                <img src="{{ asset('images/teamflow_logo.svg') }}" alt="logo" width="28" height="28">
                {{ config('app.name', 'MyApp') }}<span>.</span>
            </a>
            <span>{{ date('Y') }} · Все права защищены</span>
        </footer>

    </body>
</html>
