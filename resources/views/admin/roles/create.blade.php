@extends('layouts.admin')

@section('title', 'Create Role')

@section('page-content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-shield-plus me-2"></i>Create New Role</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="row g-3">
                    @include('admin.roles._form', ['selectedPermissions' => []])
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Role</button>
                </div>
            </form>
        </div>
    </div>
@endsection
