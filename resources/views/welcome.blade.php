<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'FOC Portal') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="welcome-body">
    <section class="welcome-wrap">
        <div class="welcome-card">
            <span class="welcome-badge">Enterprise Portal</span>
            <h1 class="welcome-title">FOC Portal</h1>
            <p class="welcome-text">Secure role-based operations for your team, built with Laravel and Bootstrap.</p>
            <div class="d-flex gap-2 flex-wrap justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-premium">Sign In</a>
            </div>
        </div>
    </section>
</body>
</html>
