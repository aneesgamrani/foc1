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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { background-color: #F5F8FA; }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #1E1E2D 0%, #23233c 50%, #1a1a2e 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-page::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(0,158,247,0.06);
            top: -200px;
            right: -150px;
        }

        .auth-page::after {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(114,57,234,0.07);
            bottom: -100px;
            left: -100px;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            padding: 40px 40px 36px;
            position: relative;
            z-index: 10;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .auth-logo .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .auth-logo .logo-text .app-name {
            font-size: 16px;
            font-weight: 700;
            color: #181C32;
            line-height: 1;
        }

        .auth-logo .logo-text .app-tagline {
            font-size: 11px;
            color: #A1A5B7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 700;
            color: #181C32;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 13px;
            color: #A1A5B7;
            margin-bottom: 28px;
        }

        .auth-footer {
            text-align: center;
            font-size: 12px;
            color: #A1A5B7;
            margin-top: 20px;
        }

        @media (max-width: 480px) {
            .auth-card { padding: 28px 22px; }
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <!-- Logo -->
            <div class="auth-logo">
                <div class="logo-icon">
                    <img src="{{ asset('assets/foc_logo.png') }}" alt="{{ config('app.name') }}" style="width:48px;height:48px;object-fit:contain;">
                </div>
                <div class="logo-text">
                    <div class="app-name">{{ config('app.name', 'FOC Portal') }}</div>
                    <div class="app-tagline">SEZ Reporting Portal</div>
                </div>
            </div>

            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset

            <div class="auth-footer">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @livewireScripts
</body>
</html>
