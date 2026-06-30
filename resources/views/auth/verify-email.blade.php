@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
    <h2 class="auth-title">Verify Email</h2>
    <p class="auth-subtitle">We sent a verification link to your email. Click it to activate your account.</p>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">A new verification link has been sent to your email address.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <div class="d-grid">
            <button type="submit" class="btn btn-premium">Resend Verification Email</button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <div class="d-grid">
            <button type="submit" class="btn btn-light-premium">Log Out</button>
        </div>
    </form>
@endsection
