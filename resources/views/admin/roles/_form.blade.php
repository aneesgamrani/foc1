<div class="row g-4">
    <div class="col-lg-5">
        <label for="name" class="form-label">Role Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $role->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Enter role name"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label d-block mb-3">Permissions</label>
        <div class="permission-grid">
            @foreach($permissions as $permission)
                <div class="form-check permission-item">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="permission_{{ $permission->id }}"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        @checked(in_array($permission->name, old('permissions', $selectedPermissions ?? []), true))
                    >
                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        </div>
        @error('permissions')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
        @error('permissions.*')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>
