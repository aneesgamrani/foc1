<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row g-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="company_name" class="form-label">Company Name</label>
            <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $user->company_name) }}" autocomplete="organization">
            @error('company_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="company_logo" class="form-label">Company Logo (Optional)</label>
            <input id="company_logo" name="company_logo" type="file" accept="image/png,image/jpeg,image/jpg,image/webp" class="form-control @error('company_logo') is-invalid @enderror">
            @error('company_logo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if ($user->company_logo)
                <div class="profile-logo-preview mt-2">
                    <img src="{{ asset('storage/'.$user->company_logo) }}" alt="{{ $user->company_name ?: $user->name }} logo">
                    <span>Current logo</span>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="col-12">
                <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2 mb-0">
                    <span>Your email address is unverified.</span>
                    <button form="send-verification" class="btn btn-sm btn-light-warning" type="submit">Resend verification email</button>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <div class="text-success small mt-2">A new verification link has been sent.</div>
                @endif
            </div>
        @endif
    </div>

    <div class="mt-4 d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success small">Saved.</span>
        @endif
    </div>
</form>
