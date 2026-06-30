@extends('layouts.admin')

@section('title', 'Edit User')

@section('page-content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-pencil-square me-2"></i>Edit User: {{ $user->name }}</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    @include('admin.users._form', ['selectedRoles' => $selectedRoles])
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update User</button>
                </div>
            </form>
        </div>
    </div>
@endsection
