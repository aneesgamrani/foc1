<div>
    <h2 class="auth-title">Sign In</h2>
    <p class="auth-subtitle">Enter your credentials to access your account</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">

        <div class="mb-3">
            <label for="login" class="form-label">Username or Email</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#F5F8FA;border-color:#EFF2F5;border-radius:8px 0 0 8px;">
                    <i class="bi bi-person" style="color:#A1A5B7;"></i>
                </span>
                <input wire:model="form.login"
                       type="text"
                       id="login"
                       class="form-control"
                       placeholder="Username or email address"
                       required autofocus autocomplete="username"
                       style="border-radius:0 8px 8px 0;">
            </div>
            <x-input-error :messages="$errors->get('form.login')" class="text-danger fs-12 mt-1" />
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-decoration-none fs-12"
                       style="color:#009EF7;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text" style="background:#F5F8FA;border-color:#EFF2F5;border-radius:8px 0 0 8px;">
                    <i class="bi bi-lock" style="color:#A1A5B7;"></i>
                </span>
                <input wire:model="form.password"
                       type="password"
                       id="password"
                       class="form-control"
                       placeholder="Enter your password"
                       required autocomplete="current-password"
                       style="border-radius:0 8px 8px 0;">
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="text-danger fs-12 mt-1" />
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input wire:model="form.remember"
                       type="checkbox"
                       id="remember"
                       class="form-check-input">
                <label for="remember" class="form-check-label fs-13 text-muted">
                    Keep me signed in
                </label>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Sign In
            </button>
        </div>

    </form>

    <div class="mt-4 text-center fs-13 text-muted">
        Access is limited to authorized users only.
    </div>
</div>
