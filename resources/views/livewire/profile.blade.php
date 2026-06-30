<div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="bi bi-person-badge-fill me-2"></i>Profile Info</h5>
                </div>
                <div class="card-body text-center">
                    <div style="width:72px;height:72px;border-radius:12px;background:var(--primary-subtle);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;margin:0 auto 12px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-1" style="color:var(--text-heading);">{{ auth()->user()->name }}</h6>
                    <div class="text-muted mb-2" style="font-size:12px;">{{ auth()->user()->email }}</div>
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                        @forelse (auth()->user()->getRoleNames() as $role)
                            <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:10px;">{{ ucfirst($role) }}</span>
                        @empty
                            <span class="kt-badge kt-badge-secondary" style="font-size:10px;">No role</span>
                        @endforelse
                    </div>
                    @if (auth()->user()->company_logo)
                    <div class="mt-3">
                        <img src="{{ asset('storage/'.auth()->user()->company_logo) }}" alt="Logo" style="max-width:100px;max-height:50px;border-radius:8px;border:1px solid var(--border);">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="Enter company name">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Logo</label>
                            <input type="file" wire:model="company_logo" accept="image/png,image/jpeg,image/jpg,image/webp" class="form-control @error('company_logo') is-invalid @enderror">
                            @error('company_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="company_logo" class="small text-muted mt-1">Uploading...</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-muted fs-11">(optional)</span></label>
                            <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-save me-1"></i>Save Changes</span>
                            <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Once you archive your account, you will be logged out and your data will be deactivated. This action is reversible by an administrator.</p>
                    <button type="button" class="btn btn-outline-danger" wire:click="$set('showDeleteConfirm', true)">
                        <i class="bi bi-archive me-1"></i>Archive My Account
                    </button>

                    @if ($showDeleteConfirm)
                    <div class="mt-4 p-3" style="border:1px solid var(--danger-subtle);border-radius:var(--radius);background:var(--danger-light);">
                        <label class="form-label text-danger fw-semibold">Enter your password to confirm:</label>
                        <div class="d-flex gap-2">
                            <input type="password" wire:model="confirmDeletePassword" class="form-control @error('confirmDeletePassword') is-invalid @enderror" placeholder="Current password" style="max-width:280px;">
                            <button type="button" class="btn btn-danger" wire:click="confirmDelete" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="bi bi-check-lg me-1"></i>Confirm Archive</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Processing...</span>
                            </button>
                            <button type="button" class="btn btn-light" wire:click="$set('showDeleteConfirm', false)">Cancel</button>
                        </div>
                        @error('confirmDeletePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
