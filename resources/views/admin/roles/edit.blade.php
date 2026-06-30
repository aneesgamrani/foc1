@extends('layouts.admin')

@section('title', 'Edit Role')

@section('page-content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Role: {{ $role->name }}</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    @include('admin.roles._form', ['selectedPermissions' => $rolePermissionNames])
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update Role</button>
                </div>
            </form>
        </div>
    </div>
@endsection
