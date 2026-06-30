@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
    <h2 class="auth-title">Confirm Password</h2>
    <p class="auth-subtitle">For security, please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Enter your password">
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-premium">Confirm</button>
        </div>
    </form>
@endsection
