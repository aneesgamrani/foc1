<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                <span class="card-icon"><i class="bi bi-people-fill"></i></span>
                User Management
            </h5>
            <div class="d-flex gap-2">
                @can('user-create')
                <button wire:click="openCreate" class="btn btn-sm btn-primary">
                    <i class="bi bi-person-plus me-1"></i> Register User
                </button>
                @endcan
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User Type</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Created By</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($user->developer_type === 1)
                                    <span class="kt-badge kt-badge-purple kt-badge-pill">Zone Developer</span>
                                @elseif ($user->developer_type === 2)
                                    <span class="kt-badge kt-badge-teal kt-badge-pill">Enterprise Developer</span>
                                @else
                                    <span class="text-muted fs-12">Unknown</span>
                                @endif
                            </td>
                            <td class="fw-semibold"
                             {{--  style="color:#181C32;" --}}
                             >
                                {{ $user->name }}
                                @if ($user->id === auth()->id())
                                    <span class="kt-badge kt-badge-success kt-badge-pill" style="font-size:9px;vertical-align:middle;">You</span>
                                @endif
                            </td>
                            <td>{{ $user->company_name ?: '-' }}</td>
                            <td>{{ $user->creator?->name ?: '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:10px;">{{ $role->name }}</span>
                                @empty
                                    <span class="text-muted fs-12">No roles</span>
                                @endforelse
                            </td>
                            <td class="text-end">
                                <button wire:click="openEdit({{ $user->id }})" class="btn btn-sm btn-light-warning" @can('user-edit') @else disabled @endcan>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if ($user->id !== auth()->id())
                                <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-sm btn-light-danger" @can('user-delete') @else disabled @endcan>
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($showForm)
    <div class="modal-backdrop fade show" style="z-index:1040;"></div>
    <div class="modal fade show d-block" tabindex="-1" style="z-index:1050;" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEditing ? 'bi-pencil-square' : 'bi-person-plus' }} me-2"></i>
                        {{ $isEditing ? 'Edit User' : 'Register New User' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="cancel"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="Enter company name">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Logo <span class="text-muted small">(Optional)</span></label>
                            <input type="file" wire:model="company_logo" accept="image/png,image/jpeg,image/jpg,image/webp" class="form-control @error('company_logo') is-invalid @enderror">
                            @error('company_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="company_logo" class="small text-muted mt-1">Uploading...</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ $isEditing ? 'Password (optional)' : 'Password' }} <span class="text-danger">*</span></label>
                            <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" {{ $isEditing ? '' : 'required' }}>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block mb-3">Developer Type <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="dev_type_zone" value="1" wire:model="developer_type" >
                                    <label class="form-check-label" for="dev_type_zone">Zone Developer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="dev_type_enterprise" value="2" wire:model="developer_type">
                                    <label class="form-check-label" for="dev_type_enterprise">Enterprise Developer</label>
                                </div>
                            </div>
                            @error('developer_type') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block mb-3">Roles <span class="text-danger">*</span></label>
                            <div class="role-grid">
                                @foreach($roles as $role)
                                <div class="form-check role-item">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="role_{{ $role->id }}"
                                        value="{{ $role->name }}"
                                        wire:model="selectedRoles"
                                    >
                                    <label class="form-check-label" for="role_{{ $role->id }}">{{ ucfirst($role->name) }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('selectedRoles') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-save me-1"></i>{{ $isEditing ? 'Update User' : 'Create User' }}</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($confirmDeleteId)
    <div class="modal-backdrop fade show" style="z-index:1040;"></div>
    <div class="modal fade show d-block" tabindex="-1" style="z-index:1050;" wire:ignore.self>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Archive</h5>
                    <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to archive this user? They will be deactivated and can be restored later.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="cancelDelete">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="bi bi-archive me-1"></i>Archive User
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
