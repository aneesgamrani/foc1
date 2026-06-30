@extends('layouts.admin')

@section('title', 'User Details')

@section('page-content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                <span class="card-icon"><i class="bi bi-person-badge-fill"></i></span>
                User: {{ $user->name }}
            </h5>
            <a href="{{ route('users.index') }}" wire:navigate class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Name</div>
                        <div class="fw-semibold" style="color:#181C32;">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Email</div>
                        <div class="fw-semibold" style="color:#181C32;">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Company Name</div>
                        <div class="fw-semibold" style="color:#181C32;">{{ $user->company_name ?: '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Developer Type</div>
                        <div class="fw-semibold" style="color:#181C32;">{{ $user->developer_type ? ucfirst($user->developer_type) : '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Created By</div>
                        <div class="fw-semibold" style="color:#181C32;">{{ $user->creator?->name ?: '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Company Logo</div>
                        <div>
                            @if ($user->company_logo)
                                <img src="{{ asset('storage/'.$user->company_logo) }}" alt="Logo" style="max-width:120px;max-height:60px;border-radius:6px;border:1px solid #EFF2F5;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <div class="fs-12 text-muted mb-1">Assigned Roles</div>
                        <div>
                            @forelse($user->roles as $role)
                                <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:10px;">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted">No roles assigned.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
