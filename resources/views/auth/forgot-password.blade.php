@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <h2 class="auth-title">Forgot Password</h2>
    <p class="auth-subtitle">Enter your email and we will send a secure reset link.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus placeholder="Enter your email">
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-premium">Send Reset Link</button>
        </div>

        <a href="{{ route('login') }}" class="auth-link">Back to Sign In</a>
    </form>
@endsection
