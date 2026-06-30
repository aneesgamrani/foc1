@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-content')
    {{-- Welcome Hero --}}
    <div class="welcome-card mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="fs-13 fw-medium" style="color:rgba(255,255,255,0.75);">Welcome back,</div>
                <h2 class="fs-24 fw-bold mb-1" style="color:#fff;">{{ auth()->user()->name }}</h2>
                <div class="d-flex gap-3 flex-wrap" style="color:rgba(255,255,255,0.7);">
                    <span><i class="bi bi-shield-check me-1"></i>
                        @foreach (auth()->user()->getRoleNames() as $role){{ ucfirst($role) }}@if(!$loop->last), @endif @endforeach
                    </span>
                    <span><i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, F j, Y') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                @can('report-create')
                <a href="{{ route('reports.index') }}" wire:navigate class="btn btn-light" style="background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.2);color:#fff;">
                    <i class="bi bi-plus-circle me-1"></i> New Report
                </a>
                @endcan
                <a href="{{ route('profile.index') }}" wire:navigate class="btn" style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.15);color:#fff;">
                    <i class="bi bi-person-gear me-1"></i> Profile
                </a>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        @can('user-list')
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-primary h-100">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-value">{{ number_format($userCount ?? 0) }}</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-trend"><i class="bi bi-person-check"></i> Registered accounts</div>
            </div>
        </div>
        @endcan
        @can('role-list')
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-purple h-100">
                <div class="stat-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <div class="stat-value">{{ number_format($roleCount ?? 0) }}</div>
                <div class="stat-label">Roles</div>
                <div class="stat-trend"><i class="bi bi-diagram-3"></i> Permission groups</div>
            </div>
        </div>
        @endcan
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-success h-100">
                <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                <div class="stat-value">{{ number_format($totalReports) }}</div>
                <div class="stat-label">Total Reports</div>
                <div class="stat-trend"><i class="bi bi-check-circle"></i> {{ $submittedReports }} submitted</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-warning h-100">
                <div class="stat-icon"><i class="bi bi-pencil-square"></i></div>
                <div class="stat-value">{{ number_format($draftReports) }}</div>
                <div class="stat-label">Draft Reports</div>
                <div class="stat-trend"><i class="bi bi-hourglass"></i> Pending submission</div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        @if (!empty($trendLabels))
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-icon" style="width:30px;height:30px;border-radius:8px;background:var(--primary-subtle);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:13px;">
                            <i class="bi bi-graph-up"></i>
                        </span>
                        Report Trend
                    </h5>
                    <span class="kt-badge kt-badge-primary kt-badge-pill">Last 6 months</span>
                </div>
                <div class="card-body">
                    <div id="chart-report-trend" style="min-height:280px;"></div>
                </div>
            </div>
        </div>
        @endif
        @if (count($audienceCounts) > 0)
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-icon" style="width:30px;height:30px;border-radius:8px;background:var(--purple-subtle);color:var(--purple);display:flex;align-items:center;justify-content:center;font-size:13px;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </span>
                        Audience
                    </h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div id="chart-audience" style="width:100%;min-height:280px;"></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Bottom Row: Report Types + Recent Reports --}}
    @if (count($reportTypeCounts) > 0)
    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-icon" style="width:30px;height:30px;border-radius:8px;background:var(--success-subtle);color:var(--success);display:flex;align-items:center;justify-content:center;font-size:13px;">
                            <i class="bi bi-bar-chart-fill"></i>
                        </span>
                        Reports by Type
                    </h5>
                </div>
                <div class="card-body">
                    <div id="chart-type" style="min-height:240px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <span class="card-icon" style="width:30px;height:30px;border-radius:8px;background:var(--teal-subtle);color:var(--teal);display:flex;align-items:center;justify-content:center;font-size:13px;">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        Recent Reports
                    </h5>
                    @can('report-list')
                    <a href="{{ route('reports.index') }}" wire:navigate class="btn btn-sm btn-light-primary">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    @endcan
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Period</th>
                                    <th>Type</th>
                                    <th>Group</th>
                                    <th>Status</th>
                                    <th class="text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentReports as $report)
                                <tr>
                                    <td class="fw-semibold" style="color:var(--text-heading);">{{ $report->periodLabel() }}</td>
                                    <td>
                                        <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:10px;">
                                            {{ ucfirst($report->report_type) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($report->audience) }}</td>
                                    <td>
                                        @php
                                            $sc = match($report->status) {
                                                'draft' => 'secondary',
                                                'submitted' => 'success',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="kt-badge kt-badge-{{ $sc }} kt-badge-pill" style="font-size:10px;">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end text-muted">{{ $report->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No reports found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Navigation --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-icon" style="width:30px;height:30px;border-radius:8px;background:var(--primary-subtle);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:13px;">
                    <i class="bi bi-grid"></i>
                </span>
                Quick Access
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                @can('report-list')
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('reports.index') }}" wire:navigate class="quick-card">
                        <div class="qc-icon" style="background:var(--primary-subtle);color:var(--primary);">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <div class="qc-title">Reports</div>
                            <div class="qc-desc">SEZ report list</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted ms-auto" style="font-size:12px;"></i>
                    </a>
                </div>
                @endcan
                @can('role-list')
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('roles.index') }}" wire:navigate class="quick-card">
                        <div class="qc-icon" style="background:var(--purple-subtle);color:var(--purple);">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <div class="qc-title">Roles</div>
                            <div class="qc-desc">Manage roles & permissions</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted ms-auto" style="font-size:12px;"></i>
                    </a>
                </div>
                @endcan
                @can('user-list')
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('users.index') }}" wire:navigate class="quick-card">
                        <div class="qc-icon" style="background:var(--success-subtle);color:var(--success);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="qc-title">Users</div>
                            <div class="qc-desc">Manage user accounts</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted ms-auto" style="font-size:12px;"></i>
                    </a>
                </div>
                @endcan
                @can('report-create')
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('reports.index') }}" wire:navigate class="quick-card">
                        <div class="qc-icon" style="background:var(--warning-subtle);color:var(--warning);">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                        <div>
                            <div class="qc-title">New Report</div>
                            <div class="qc-desc">Create a new SEZ report</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted ms-auto" style="font-size:12px;"></i>
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('livewire:navigated', function() {
    const getCS = (v) => getComputedStyle(document.documentElement).getPropertyValue(v).trim();

    var colors = {
        primary: getCS('--primary') || '#2563EB',
        success: getCS('--success') || '#059669',
        warning: getCS('--warning') || '#D97706',
        purple: getCS('--purple') || '#7C3AED',
        teal: getCS('--teal') || '#0D9488',
        danger: getCS('--danger') || '#DC2626',
    };

    @if (!empty($trendLabels))
    var trendEl = document.querySelector('#chart-report-trend');
    if (trendEl && !trendEl.hasChildNodes()) {
        new ApexCharts(trendEl, {
            chart: { type: 'area', height: 280, toolbar: { show: false } },
            series: [{ name: 'Reports', data: @json($trendValues) }],
            xaxis: { categories: @json($trendLabels), axisBorder: { show: false }, axisTicks: { show: false } },
            colors: [colors.primary],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.02 } },
            stroke: { curve: 'smooth', width: 2.5 },
            dataLabels: { enabled: false },
            markers: { size: 4, strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
            grid: { borderColor: 'var(--border)', strokeDashArray: 4 },
            legend: { show: false },
            yaxis: { min: 0, forceNiceScale: true, labels: { style: { colors: 'var(--text-muted)', fontSize: '11px' } } },
            tooltip: { theme: 'light', x: { format: 'MMM yyyy' } },
        }).render();
    }
    @endif

    @if (count($audienceCounts) > 0)
    var audienceEl = document.querySelector('#chart-audience');
    if (audienceEl && !audienceEl.hasChildNodes()) {
        new ApexCharts(audienceEl, {
            chart: { type: 'donut', height: 280, background: 'transparent' },
            series: @json(array_values(collect($audienceCounts)->toArray())),
            labels: @json(array_keys(collect($audienceCounts)->toArray())),
            colors: [colors.primary, colors.success, colors.purple],
            legend: { position: 'bottom', fontSize: '12px', labels: { colors: 'var(--text-muted)' } },
            plotOptions: { pie: { donut: { size: '62%', labels: { show: true, total: { show: true, fontSize: '14px', fontWeight: 600, color: 'var(--text-heading)' } } } } },
            dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 600 } },
            stroke: { width: 0 },
            tooltip: { theme: 'light' },
        }).render();
    }
    @endif

    @if (count($reportTypeCounts) > 0)
    var typeEl = document.querySelector('#chart-type');
    if (typeEl && !typeEl.hasChildNodes()) {
        new ApexCharts(typeEl, {
            chart: { type: 'bar', height: 240, toolbar: { show: false } },
            series: [{ name: 'Reports', data: @json(array_values(collect($reportTypeCounts)->toArray())) }],
            xaxis: { categories: @json(array_keys(collect($reportTypeCounts)->toArray())), labels: { style: { colors: 'var(--text-muted)', fontSize: '11px' } } },
            colors: [colors.teal],
            plotOptions: { bar: { borderRadius: 6, horizontal: false, columnWidth: '55%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 600 }, offsetY: -4 },
            grid: { borderColor: 'var(--border)', strokeDashArray: 4 },
            legend: { show: false },
            yaxis: { labels: { style: { colors: 'var(--text-muted)', fontSize: '11px' } } },
            tooltip: { theme: 'light' },
        }).render();
    }
    @endif
});
</script>
@endpush
