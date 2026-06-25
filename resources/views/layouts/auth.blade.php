<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Trackit'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.style.colorScheme = theme;
        })();
    </script>

    <style>
        :root {
            --auth-text: #0f172a;
            --auth-muted: #64748b;
            --auth-border: #e2e8f0;
            --auth-bg: #ffffff;
            --auth-soft: #f8fafc;
            --auth-primary: #2563eb;
            --auth-shadow: 0 20px 48px rgba(15, 23, 42, 0.10);
            --auth-hero: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        html[data-theme="dark"] {
            --auth-text: #f1f5f9;
            --auth-muted: #94a3b8;
            --auth-border: #334155;
            --auth-bg: #0f172a;
            --auth-soft: #111827;
            --auth-primary: #60a5fa;
            --auth-shadow: 0 24px 54px rgba(2, 6, 23, 0.42);
            --auth-hero: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        }

        * {
            box-sizing: border-box;
        }

        html {
            color-scheme: light;
        }

        html[data-theme="dark"] {
            color-scheme: dark;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--auth-text);
            background: var(--auth-hero);
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(380px, 460px);
        }

        .auth-hero {
            position: relative;
            display: flex;
            align-items: center;
            padding: 3rem;
            overflow: hidden;
            background: var(--auth-hero);
            border-right: 1px solid var(--auth-border);
        }

        .auth-hero-inner,
        .auth-panel {
            position: relative;
            z-index: 1;
        }

        .auth-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
        }

        .auth-logo {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #ffffff;
        }

        .auth-hero h1 {
            margin: 1.4rem 0 0.9rem;
            font-size: clamp(2rem, 3.4vw, 3.25rem);
            line-height: 1;
            letter-spacing: -0.04em;
            max-width: 10ch;
        }

        .auth-hero p {
            max-width: 32rem;
            color: var(--auth-muted);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .auth-feature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .auth-feature {
            padding: 0.95rem 1rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--auth-border);
            backdrop-filter: blur(12px);
        }

        .auth-feature .label {
            display: block;
            color: var(--auth-muted);
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 0.45rem;
        }

        .auth-feature .value {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .auth-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            position: relative;
        }

        .auth-card {
            width: 100%;
            max-width: 28rem;
            border-radius: 1.5rem;
            background: var(--auth-bg);
            border: 1px solid var(--auth-border);
            box-shadow: var(--auth-shadow);
            overflow: hidden;
        }

        .auth-card-header {
            padding: 1.75rem 1.75rem 0;
        }

        .auth-card-body {
            padding: 1.35rem 1.75rem 1.75rem;
        }

        .auth-card-body h2 {
            font-size: 1.5rem;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 0.35rem;
        }

        .auth-subtitle {
            color: var(--auth-muted);
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--auth-text);
            margin-bottom: 0.45rem;
        }

        .form-control {
            min-height: 3rem;
            border-radius: 0.9rem;
            border-color: var(--auth-border);
            padding-inline: 0.95rem;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 0.22rem rgba(37, 99, 235, 0.12);
        }

        .auth-actions .btn {
            min-height: 3rem;
            border-radius: 0.9rem;
            font-weight: 700;
        }

        .btn-auth-primary {
            background: #0f172a;
            border: 0;
        }

        .btn-auth-primary:hover {
            filter: brightness(1.04);
            transform: translateY(-1px);
        }

        .auth-footer {
            padding: 0 1.75rem 1.75rem;
            text-align: center;
            color: var(--auth-muted);
            font-size: 0.92rem;
        }

        .auth-link {
            color: var(--auth-primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .auth-mini {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.10);
            color: var(--auth-primary);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .alert {
            border-radius: 1rem;
            border: 1px solid var(--auth-border);
        }

        .auth-theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 1px solid var(--auth-border);
            background: rgba(255, 255, 255, 0.86);
            color: var(--auth-text);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        html[data-theme="dark"] .auth-theme-toggle {
            background: rgba(15, 23, 42, 0.82);
        }

        .auth-theme-toggle:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 991.98px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-hero {
                padding: 1.75rem 1.25rem;
                min-height: 220px;
            }

            .auth-hero h1 {
                max-width: 100%;
            }

            .auth-panel {
                padding: 1rem;
            }

            .auth-card {
                max-width: none;
            }
        }

        @media (max-width: 575.98px) {
            .auth-hero {
                padding: 1.25rem;
            }

            .auth-card-header,
            .auth-card-body,
            .auth-footer {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }

            .auth-feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="auth-shell">
        <section class="auth-hero d-none d-lg-flex">
            <div class="auth-hero-inner">
                <div class="auth-brand">
                    <span class="auth-logo"><i class="bi bi-window-sidebar"></i></span>
                    <span>{{ config('app.name', 'Trackit') }}</span>
                </div>
                <h1>Simple access for a cleaner workflow.</h1>
                <p>Sign in or create an account with a calm, readable layout.</p>

                <div class="auth-feature-grid">
                    <div class="auth-feature">
                        <span class="label">Clear</span>
                        <span class="value">Focused form layout</span>
                    </div>
                    <div class="auth-feature">
                        <span class="label">Simple</span>
                        <span class="value">Minimal visual noise</span>
                    </div>
                    <div class="auth-feature">
                        <span class="label">Fast</span>
                        <span class="value">Quick sign in and signup</span>
                    </div>
                    <div class="auth-feature">
                        <span class="label">Responsive</span>
                        <span class="value">Works well on every screen</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="auth-panel">

            <div class="auth-card">
                @yield('content')
            </div>
        </section>
    </div>
</body>

</html>