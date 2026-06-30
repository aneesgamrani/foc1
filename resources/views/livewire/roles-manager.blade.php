<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                <span class="card-icon"><i class="bi bi-shield-lock-fill"></i></span>
                Role Management
            </h5>
            @can('role-create')
            <button wire:click="openCreate" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Create Role
            </button>
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold" 
                                {{--  style="color:#181C32;" --}}
                                >
                                {{ $role->name }}
                            </td>
                            <td>
                                <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:10px;">
                                    {{ $role->permissions_count }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button wire:click="openEdit({{ $role->id }})" class="btn btn-sm btn-light-warning" @can('role-edit') @else disabled @endcan>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $role->id }})" class="btn btn-sm btn-light-danger" @can('role-delete') @else disabled @endcan>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No roles found.</td>
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
                    <h5 class="modal-title">{{ $isEditing ? 'Edit Role' : 'Create Role' }}</h5>
                    <button type="button" class="btn-close" wire:click="cancel"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Role Name</label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter role name">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <label class="form-label d-block mb-3">Permissions</label>
                    <div class="permission-grid">
                        @foreach($permissions as $permission)
                        <div class="form-check permission-item">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="perm_{{ $permission->id }}"
                                value="{{ $permission->name }}"
                                wire:model="selectedPermissions"
                            >
                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('selectedPermissions') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="bi bi-save me-1"></i>{{ $isEditing ? 'Update Role' : 'Create Role' }}
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
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this role? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="cancelDelete">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
