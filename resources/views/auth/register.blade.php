@extends('layouts.guest')

@section('title', 'Create Account')

@section('content')
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-subtitle">Set up your account to start managing your organization securely.</p>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="auth-form">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter your full name"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-building"></i></span>
                <input
                    id="company_name"
                    type="text"
                    name="company_name"
                    value="{{ old('company_name') }}"
                    class="form-control @error('company_name') is-invalid @enderror"
                    placeholder="Enter your company name"
                    required
                    autocomplete="organization"
                >
            </div>
            @error('company_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="company_logo" class="form-label">Company Logo (Optional)</label>
            <input
                id="company_logo"
                type="file"
                name="company_logo"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="form-control @error('company_logo') is-invalid @enderror"
            >
            <div class="form-text">Accepted: JPG, PNG, WEBP. Max 2MB.</div>
            @error('company_logo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Enter your email"
                    required
                    autocomplete="username"
                >
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Create a password"
                    required
                    autocomplete="new-password"
                >
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirm your password"
                    required
                    autocomplete="new-password"
                >
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-premium btn-lg">
                <i class="bi bi-person-plus me-1"></i>
                Register
            </button>
        </div>

        <p class="auth-switch-text mb-0">
            Already have an account?
            <a href="{{ route('login') }}" class="auth-link">Sign In</a>
        </p>
    </form>
@endsection
