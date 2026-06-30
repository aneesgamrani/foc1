<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Inter Font (local) -->
    <link href="{{ asset('libs/fonts/inter/inter.css') }}" rel="stylesheet">

    <!-- Bootstrap 5 (local) -->
    <link href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons (local) -->
    <link href="{{ asset('libs/bootstrap-icons/css/bootstrap-icons.min.css') }}" rel="stylesheet">
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
</head>
<body>

    @auth
        {{-- Sidebar + Header rendered by Livewire navigation component --}}
        <livewire:layout.navigation />

        {{-- Page wrapper --}}
        <div class="kt-wrapper" id="kt_wrapper">

            {{-- Toolbar / Sub-header --}}
            @isset($header)
                <div class="kt-toolbar">
                    {{ $header }}
                </div>
            @endisset

            {{-- Main content --}}
            <div class="kt-content">

                {{-- Flash messages --}}
                @if (session()->has('success'))
                    <div class="kt-notice success mb-2">
                        <i class="bi bi-check-circle-fill notice-icon"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="kt-notice danger mb-2">
                        <i class="bi bi-exclamation-circle-fill notice-icon"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if (session()->has('status'))
                    <div class="kt-notice success mb-2">
                        <i class="bi bi-check-circle-fill notice-icon"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>

        </div>

    @else
        {{ $slot }}
    @endauth

    @livewireScripts
    @stack('scripts')
    <script data-navigate-once>
    (function() {
        const html = document.documentElement;

        const setTheme = (theme) => {
            html.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: theme }));
        };

        const applySavedTheme = () => {
            const saved = localStorage.getItem('theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            setTheme(saved);
        };

        applySavedTheme();

        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('#kt_theme_toggle');
            if (!toggle) return;
            const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            const icon = toggle.querySelector('i');
            if (icon) icon.style.transform = 'rotate(360deg)';
            setTimeout(() => { setTheme(next); if (icon) icon.style.transform = ''; }, 150);
        });

        document.addEventListener('livewire:navigated', applySavedTheme);
    })();
    </script>
</body>
</html>
