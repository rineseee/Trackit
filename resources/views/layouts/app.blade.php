<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<script>
    (function () {
        var theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-bs-theme', theme);
    })();
</script>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Trackit - manage projects and issues in one clean workspace">

    <title>@yield('title', config('app.name', 'Trackit')) - {{ config('app.name', 'Trackit') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    @stack('styles')
</head>

<body>
    <div class="trackit-layout">
        <x-trackit-sidebar />
        <div class="trackit-sidebar-backdrop" id="sidebarBackdrop" hidden></div>

        <div class="trackit-main">
            <x-global-navbar />

            <main class="trackit-page">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>

        <x-global-ai-assistant />
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <button class="trackit-fab" id="globalAiFab" aria-label="Open AI Assistant" title="AI Assistant">
        <i class="bi bi-robot"></i>
    </button>

    @stack('scripts')
</body>

</html>
<style>
    .trackit-fab {
        position: fixed;
        bottom: 24px;
        right: 24px;

        width: 56px;
        height: 56px;
        border-radius: 50%;

        border: none;
        background: var(--trackit-primary);

        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

        cursor: pointer;
        z-index: 1050;

        transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 22px;
    }

    .trackit-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }

    .trackit-fab:active {
        transform: scale(0.95);
    }

    .trackit-fab.hidden {
        opacity: 0;
        transform: scale(0.6);
        pointer-events: none;
    }
</style>