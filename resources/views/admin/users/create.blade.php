@extends('layouts.admin')

@section('title', 'Create User')

@section('page-content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus me-2"></i>Create New User</h5>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info py-2 small">Created by: {{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    @include('admin.users._form', ['selectedRoles' => []])
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save User</button>
                </div>
            </form>
        </div>
    </div>
@endsection
