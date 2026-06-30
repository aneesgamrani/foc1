<div class="row g-4">
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="company_name" class="form-label">Company Name</label>
        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name ?? '') }}" class="form-control @error('company_name') is-invalid @enderror" placeholder="Enter company name">
        @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="company_logo" class="form-label">Company Logo (Optional)</label>
        <input type="file" id="company_logo" name="company_logo" accept="image/png,image/jpeg,image/jpg,image/webp" class="form-control @error('company_logo') is-invalid @enderror">
        @error('company_logo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if (isset($user) && ! empty($user->company_logo))
            <div class="profile-logo-preview mt-2">
                <img src="{{ asset('storage/'.$user->company_logo) }}" alt="{{ $user->company_name ?: $user->name }} logo">
                <span>Current logo</span>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">{{ isset($user) ? 'Password (optional)' : 'Password' }}</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label d-block mb-3">Developer Type</label>
        <div class="d-flex gap-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="developer_type_zone" name="developer_type" value="zone" @checked(old('developer_type', $user->developer_type ?? '') === 'zone')>
                <label class="form-check-label" for="developer_type_zone">Zone Developer</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="developer_type_enterprise" name="developer_type" value="enterprise" @checked(old('developer_type', $user->developer_type ?? '') === 'enterprise')>
                <label class="form-check-label" for="developer_type_enterprise">Enterprise Developer</label>
            </div>
        </div>
        @error('developer_type')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label d-block mb-3">Roles</label>
        <div class="role-grid">
            @foreach($roles as $role)
                <div class="form-check role-item">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="role_{{ $role->id }}"
                        name="roles[]"
                        value="{{ $role->name }}"
                        @checked(in_array($role->name, old('roles', $selectedRoles ?? []), true))
                    >
                    <label class="form-check-label" for="role_{{ $role->id }}">{{ ucfirst($role->name) }}</label>
                </div>
            @endforeach
        </div>
        @error('roles')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
        @error('roles.*')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>
