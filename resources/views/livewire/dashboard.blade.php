<div>

    {{-- Page Header --}}
    <div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="letter-spacing:-0.02em;">Dashboard</h3>
            <p class="text-muted mb-0 small">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, F j, Y') }}
                @if ($developerType === 1)
                    &nbsp;·&nbsp;<span class="text-primary fw-semibold"><i class="bi bi-building me-1"></i>Zone Developer</span>
                @elseif ($developerType === 2)
                    &nbsp;·&nbsp;<span class="text-success fw-semibold"><i class="bi bi-buildings me-1"></i>Zone Enterprise</span>
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            @can('report-create')
            <a href="{{ route('reports.index') }}" wire:navigate class="btn btn-primary btn-sm px-3 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>New Report
            </a>
            @endcan
            <a href="{{ route('profile.index') }}" wire:navigate class="btn btn-outline-secondary btn-sm px-3 fw-semibold">
                <i class="bi bi-person-gear me-1"></i>Profile
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Reports --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--primary) !important;">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Total Reports</span>
                        <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--primary-subtle);color:var(--primary);">
                            <i class="bi bi-file-earmark-text-fill" style="font-size:1rem;"></i>
                        </span>
                    </div>
                    <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ number_format($totalReports) }}</div>
                    <div class="text-muted mt-1" style="font-size:.78rem;">
                        <span class="text-success me-2"><i class="bi bi-check-circle-fill me-1"></i>{{ $submittedReports }} submitted</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Draft Reports --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--warning) !important;">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Draft Reports</span>
                        <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--warning-subtle);color:var(--warning);">
                            <i class="bi bi-pencil-square" style="font-size:1rem;"></i>
                        </span>
                    </div>
                    <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ number_format($draftReports) }}</div>
                    <div class="text-muted mt-1" style="font-size:.78rem;">
                        <i class="bi bi-hourglass me-1"></i>Pending submission
                    </div>
                </div>
            </div>
        </div>

        {{-- Third card: context-aware --}}
        @if ($developerType === 1)
            {{-- Zone Developer: Enterprise reports available --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--success) !important;">
                    <div class="card-body px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Enterprise Reports</span>
                            <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--success-subtle);color:var(--success);">
                                <i class="bi bi-buildings-fill" style="font-size:1rem;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ number_format($enterpriseReportCount ?? 0) }}</div>
                        <div class="text-muted mt-1" style="font-size:.78rem;">
                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>{{ $enterpriseSubmittedCount ?? 0 }} submitted</span>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($userCount !== null)
            {{-- Admin/Manager: User count --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--success) !important;">
                    <div class="card-body px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Total Users</span>
                            <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--success-subtle);color:var(--success);">
                                <i class="bi bi-people-fill" style="font-size:1rem;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ number_format($userCount) }}</div>
                        <div class="text-muted mt-1" style="font-size:.78rem;"><i class="bi bi-person-check me-1"></i>Registered accounts</div>
                    </div>
                </div>
            </div>
        @else
            {{-- Enterprise / no extra stat: submitted count large --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--success) !important;">
                    <div class="card-body px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Submitted</span>
                            <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--success-subtle);color:var(--success);">
                                <i class="bi bi-send-check-fill" style="font-size:1rem;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ number_format($submittedReports) }}</div>
                        <div class="text-muted mt-1" style="font-size:.78rem;"><i class="bi bi-check-all me-1"></i>Completed &amp; submitted</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Fourth card: Roles (admin) or audience type count --}}
        @if ($roleCount !== null)
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--purple) !important;">
                    <div class="card-body px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Roles</span>
                            <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--purple-subtle);color:var(--purple);">
                                <i class="bi bi-shield-lock-fill" style="font-size:1rem;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ number_format($roleCount) }}</div>
                        <div class="text-muted mt-1" style="font-size:.78rem;"><i class="bi bi-diagram-3 me-1"></i>Permission groups</div>
                    </div>
                </div>
            </div>
        @else
            {{-- Show report type count for non-admin --}}
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid var(--teal) !important;">
                    <div class="card-body px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">Report Types</span>
                            <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--teal-subtle);color:var(--teal);">
                                <i class="bi bi-bar-chart-steps" style="font-size:1rem;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;letter-spacing:-0.03em;">{{ count($reportTypeCounts) }}</div>
                        <div class="text-muted mt-1" style="font-size:.78rem;"><i class="bi bi-layers me-1"></i>Distinct types used</div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Charts Row --}}
    @if (!empty($trendLabels) || count($audienceCounts) > 0)
    <div class="row g-3 mb-4">

        @if (!empty($trendLabels))
        <div class="{{ count($audienceCounts) > 0 ? 'col-lg-8' : 'col-12' }}">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body px-4 pt-4 pb-2">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-0">Report Trend</h6>
                            <p class="text-muted mb-0" style="font-size:.78rem;">Reports submitted over the last 6 months</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold" style="font-size:.7rem;">Last 6 months</span>
                    </div>
                    <div id="chart-report-trend"></div>
                </div>
            </div>
        </div>
        @endif

        @if (count($audienceCounts) > 0)
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body px-4 pt-4 pb-2">
                    <div class="mb-3">
                        <h6 class="fw-bold mb-0">By Audience</h6>
                        <p class="text-muted mb-0" style="font-size:.78rem;">Developer vs Enterprise split</p>
                    </div>
                    <div id="chart-audience"></div>
                    {{-- Legend --}}
                    <div class="d-flex justify-content-center gap-3 mt-2 pb-2">
                        @php $aColors = ['developer'=>'var(--primary)','enterprise'=>'var(--success)']; @endphp
                        @foreach ($audienceCounts as $aud => $cnt)
                        <div class="d-flex align-items-center gap-1" style="font-size:.78rem;">
                            <span class="rounded-circle d-inline-block" style="width:8px;height:8px;background:{{ $aColors[$aud] ?? 'var(--purple)' }};"></span>
                            <span class="text-muted">{{ ucfirst($aud) }}</span>
                            <span class="fw-semibold">{{ $cnt }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
    @endif

    {{-- Recent Reports --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-3 border-bottom">
                <div>
                    <h6 class="fw-bold mb-0">Recent Reports</h6>
                    <p class="text-muted mb-0" style="font-size:.78rem;">Latest activity across all report submissions</p>
                </div>
                @can('report-list')
                <a href="{{ route('reports.index') }}" wire:navigate class="btn btn-sm btn-outline-primary fw-semibold px-3" style="font-size:.8rem;">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
                    <thead>
                        <tr style="background:var(--kt-body-bg, transparent);">
                            <th class="px-4 py-3 fw-semibold text-muted text-uppercase border-0" style="font-size:.7rem;letter-spacing:.05em;">Period</th>
                            <th class="py-3 fw-semibold text-muted text-uppercase border-0" style="font-size:.7rem;letter-spacing:.05em;">Audience</th>
                            <th class="py-3 fw-semibold text-muted text-uppercase border-0" style="font-size:.7rem;letter-spacing:.05em;">Type</th>
                            @if ($userCount !== null)
                            <th class="py-3 fw-semibold text-muted text-uppercase border-0" style="font-size:.7rem;letter-spacing:.05em;">Owner</th>
                            @endif
                            <th class="py-3 fw-semibold text-muted text-uppercase border-0" style="font-size:.7rem;letter-spacing:.05em;">Status</th>
                            <th class="py-3 fw-semibold text-muted text-uppercase border-0 text-end pe-4" style="font-size:.7rem;letter-spacing:.05em;">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReports as $report)
                        <tr>
                            <td class="px-4 py-3 fw-semibold" style="color:var(--text-heading,inherit);">
                                <a href="{{ route('reports.show', $report) }}" wire:navigate class="text-decoration-none text-inherit stretched-link-inner">
                                    {{ $report->periodLabel() }}
                                </a>
                            </td>
                            <td class="py-3">
                                @if ($report->audience === 'developer')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:.7rem;">Developer</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem;">Enterprise</span>
                                @endif
                            </td>
                            <td class="py-3 text-muted" style="font-size:.82rem;">{{ ucfirst($report->report_type) }}</td>
                            @if ($userCount !== null)
                            <td class="py-3 text-muted" style="font-size:.82rem;">{{ $report->user?->name ?? '—' }}</td>
                            @endif
                            <td class="py-3">
                                @if ($report->status === 'submitted')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem;"><i class="bi bi-check-circle-fill me-1"></i>Submitted</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size:.7rem;"><i class="bi bi-pencil me-1"></i>Draft</span>
                                @endif
                            </td>
                            <td class="py-3 pe-4 text-muted text-end" style="font-size:.78rem;">{{ $report->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox d-block mb-2 fs-3 text-secondary"></i>
                                No reports found yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Nav --}}
    <div class="d-flex flex-wrap gap-2">
        @can('report-list')
        <a href="{{ route('reports.index') }}" wire:navigate class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none fw-semibold border" style="font-size:.82rem;background:var(--bs-body-bg);color:var(--text-heading,inherit);transition:all .15s;">
            <i class="bi bi-file-earmark-text" style="color:var(--primary);"></i>Reports
        </a>
        @endcan
        @can('user-list')
        <a href="{{ route('users.index') }}" wire:navigate class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none fw-semibold border" style="font-size:.82rem;background:var(--bs-body-bg);color:var(--text-heading,inherit);transition:all .15s;">
            <i class="bi bi-people" style="color:var(--success);"></i>Users
        </a>
        @endcan
        @can('role-list')
        <a href="{{ route('roles.index') }}" wire:navigate class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none fw-semibold border" style="font-size:.82rem;background:var(--bs-body-bg);color:var(--text-heading,inherit);transition:all .15s;">
            <i class="bi bi-shield-lock" style="color:var(--purple);"></i>Roles
        </a>
        @endcan
        <a href="{{ route('profile.index') }}" wire:navigate class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none fw-semibold border" style="font-size:.82rem;background:var(--bs-body-bg);color:var(--text-heading,inherit);transition:all .15s;">
            <i class="bi bi-person-gear" style="color:var(--warning);"></i>Profile
        </a>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('livewire:navigated', function () {
    const css = (v) => getComputedStyle(document.documentElement).getPropertyValue(v).trim();
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const labelColor = isDark ? '#8b95a5' : '#6b7280';

    @if (!empty($trendLabels))
    (function () {
        var el = document.querySelector('#chart-report-trend');
        if (!el || el.hasChildNodes()) return;
        new ApexCharts(el, {
            chart: { type: 'area', height: 240, toolbar: { show: false }, background: 'transparent', sparkline: { enabled: false } },
            series: [{ name: 'Reports', data: @json($trendValues) }],
            xaxis: {
                categories: @json($trendLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: labelColor, fontSize: '11px' } },
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                labels: { style: { colors: labelColor, fontSize: '11px' } },
            },
            colors: ['#2563EB'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 100] } },
            stroke: { curve: 'smooth', width: 2 },
            dataLabels: { enabled: false },
            markers: { size: 4, colors: ['#2563EB'], strokeColors: isDark ? '#1e2330' : '#fff', strokeWidth: 2, hover: { size: 6 } },
            grid: { borderColor: gridColor, strokeDashArray: 4, padding: { left: 4, right: 4 } },
            tooltip: { theme: isDark ? 'dark' : 'light' },
            legend: { show: false },
        }).render();
    })();
    @endif

    @if (count($audienceCounts) > 0)
    (function () {
        var el = document.querySelector('#chart-audience');
        if (!el || el.hasChildNodes()) return;
        new ApexCharts(el, {
            chart: { type: 'donut', height: 200, background: 'transparent' },
            series: @json(array_values(collect($audienceCounts)->toArray())),
            labels: @json(array_map('ucfirst', array_keys(collect($audienceCounts)->toArray()))),
            colors: ['#2563EB', '#059669', '#7C3AED'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: { show: true, label: 'Total', fontSize: '12px', fontWeight: 600, color: labelColor, formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) },
                            value: { fontSize: '18px', fontWeight: 700, color: isDark ? '#e2e8f0' : '#111827' },
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: { width: 0 },
            legend: { show: false },
            tooltip: { theme: isDark ? 'dark' : 'light' },
        }).render();
    })();
    @endif
});
</script>
@endpush
