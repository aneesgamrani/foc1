<div>
    <div class="container-fluid px-4 py-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-circle"
                        style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-file-earmark-text-fill text-white" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-600">Report Entries</h5>
                        <small class="text-muted">Manage your submitted reports</small>
                    </div>
                </div>
                @can('report-create')
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#createReportModal">
                        <i class="bi bi-plus-circle me-2"></i> New Report
                    </button>
                @endcan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light border-top">
                            <tr>
                                <th class="" >#</th>
                                <th class="">Type</th>
                                <th class="">Group</th>
                                <th class="">Period</th>
                                <th class="">Year</th>
                                <th class="">Status</th>
                                <th class="">Created</th>
                                <th class="">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            @forelse ($reports as $report)
                                <tr wire:key="report-{{ $report->id }}" class="border-bottom">
                                    <td class="">{{ $loop->iteration }}</td>
                                    <td class="">
                                        <span class="kt-badge kt-badge-{{ $report->report_type === 'monthly' ? 'primary' : ($report->report_type === 'quarterly' ? 'purple' : ($report->report_type === 'biannual' ? 'warning' : 'secondary' )) }} ">
                                            {{ ucfirst($report->report_type) }}
                                        </span>
                                    </td>
                                    <td class="">{{ ucfirst($report->audience) }}</td>
                                    <td class="">{{ $report->periodLabel() }}</td>
                                    <td class="">{{ $report->report_year }}</td>
                                    <td class="">
                                        @php
                                            $sc = match ($report->status) {
                                                'draft' => 'secondary',
                                                'submitted' => 'success',
                                                default => 'secondary'
                                            };
                                            $statusText = match ($report->status) {
                                                'draft' => 'text-secondary',
                                                'submitted' => 'text-success',
                                                default => 'text-secondary'
                                            };
                                        @endphp
                                        <span class="kt-badge kt-badge-{{ $sc }} {{ $statusText }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="">{{ $report->created_at?->format('d M Y') }}</td>
                                    <td class="">
                                        <a href="{{ route('reports.show', $report) }}" wire:navigate
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-6 text-muted">
                                        <i class="bi bi-inbox" style="font-size: 32px; opacity: 0.5;"></i>
                                        <p class="mt-3 mb-0">No report entries found. Create your first report to get
                                            started.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="createReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-white border-bottom px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle"
                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-plus-circle text-white" style="font-size: 18px;"></i>
                        </div>
                        <h5 class="modal-title mb-0 fw-600">Create New Report</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label fw-500 mb-2">Audience <span class="text-danger">*</span></label>
                            <select wire:model="audience" class="form-select @error('audience') is-invalid @enderror"
                                required>
                                <option value="">Select audience...</option>
                                <option value="developer">Zone Developer</option>
                                <option value="enterprise">Zone Enterprise</option>
                            </select>
                            @error('audience') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-500 mb-2">Report Type <span class="text-danger">*</span></label>
                            <select wire:model.change="report_type"
                                class="form-select @error('report_type') is-invalid @enderror" required>
                                <option value="">Select report type...</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="biannual">Biannual (6-month)</option>
                                <option value="annual">Annual</option>
                            </select>
                            @error('report_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6" id="month_field" style="display:none;">
                            <label class="form-label fw-500 mb-2">Month <span class="text-danger">*</span></label>
                            <select wire:model="report_month"
                                class="form-select @error('report_month') is-invalid @enderror">
                                <option value="">Select month...</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                @endforeach
                            </select>
                            @error('report_month') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6" id="quarter_field" style="display:none;">
                            <label class="form-label fw-500 mb-2">Quarter <span class="text-danger">*</span></label>
                            <select wire:model="report_quarter"
                                class="form-select @error('report_quarter') is-invalid @enderror">
                                <option value="">Select quarter...</option>
                                @foreach (range(1, 4) as $q)
                                    <option value="{{ $q }}">Q{{ $q }}</option>
                                @endforeach
                            </select>
                            @error('report_quarter') <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6" id="half_field" style="display:none;">
                            <label class="form-label fw-500 mb-2">Half <span class="text-danger">*</span></label>
                            <select wire:model="biannual_half"
                                class="form-select @error('biannual_half') is-invalid @enderror">
                                <option value="">Select half...</option>
                                <option value="1">First Half (Jan-Jun)</option>
                                <option value="2">Second Half (Jul-Dec)</option>
                            </select>
                            @error('biannual_half') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-500 mb-2">Year <span class="text-danger">*</span></label>
                            <select wire:model="report_year"
                                class="form-select @error('report_year') is-invalid @enderror" required>
                                <option value="">Select year...</option>
                                @foreach ($years as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                            @error('report_year') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-save me-2"></i>Create Report</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm me-2"></span>Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script data-navigate-once>
            document.addEventListener('livewire:init', function () {
                const modal = document.getElementById('createReportModal');
                if (!modal) return;

                modal.addEventListener('show.bs.modal', function () {
                    Livewire.dispatch('resetCreateForm');
                });

                modal.addEventListener('hidden.bs.modal', function () {
                    Livewire.dispatch('resetCreateForm');
                });
            });

            document.addEventListener('livewire:navigated', function () {
                const typeSelect = document.querySelector('#createReportModal [wire\\:model\\.change="report_type"]');
                if (!typeSelect) return;

                function togglePeriodFields() {
                    const val = typeSelect.value;
                    document.getElementById('month_field').style.display = val === 'monthly' ? 'block' : 'none';
                    document.getElementById('quarter_field').style.display = val === 'quarterly' ? 'block' : 'none';
                    document.getElementById('half_field').style.display = val === 'biannual' ? 'block' : 'none';
                }

                typeSelect.addEventListener('change', togglePeriodFields);
                setTimeout(togglePeriodFields, 100);
            });
        </script>
    @endpush
</div>