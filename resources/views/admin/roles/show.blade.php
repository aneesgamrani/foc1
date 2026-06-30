@extends('layouts.admin')

@section('title', 'Role Details')

@section('page-content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                <span class="card-icon"><i class="bi bi-shield-lock-fill"></i></span>
                Role: {{ $role->name }}
            </h5>
            <a href="{{ route('roles.index') }}" wire:navigate class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body">
            <h6 class="fw-semibold mb-3" style="color:#181C32;">Assigned Permissions</h6>
            @if($role->permissions->isNotEmpty())
                <div class="d-flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:11px;padding:4px 10px;">{{ $permission->name }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No permissions assigned.</p>
            @endif
        </div>
    </div>
@endsection
