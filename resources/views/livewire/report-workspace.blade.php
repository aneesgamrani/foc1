<div>
    <!-- Top Header Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 py-3 px-4">
            <div>
                <span class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Report Overview</span>
                <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">{{ ucfirst($report->audience) }} Report - {{ $report->periodLabel() }}</h4>
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <span><strong>Type:</strong> {{ ucfirst($report->report_type) }}</span>
                    <span class="text-black-50">•</span>
                    <span><strong>Year:</strong> {{ $report->report_year }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @php
                    $sc = match($report->status) {
                        'draft' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                        'submitted' => 'bg-success-subtle text-success border-success-subtle',
                        default => 'bg-secondary-subtle text-secondary border-secondary-subtle'
                    };
                @endphp
                <span class="badge border rounded-pill {{ $sc }} px-3 py-1.5" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                    {{ ucfirst($report->status) }}
                </span>
                @if ($report->status !== 'submitted' && auth()->user()->can('report-submit'))
                    <button type="button" class="btn btn-primary btn-sm px-3 py-1.5 fw-semibold" wire:click="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-send-fill me-1"></i>Submit</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Submitting...</span>
                    </button>
                @endif
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="row g-4">
        <!-- Left Sidebar: Sections Navigation -->
        <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3 pb-1">
                    <h6 class="fw-bold text-uppercase text-muted tracking-wider mb-0" style="font-size: 0.75rem;">Sections</h6>
                </div>
                <div class="card-body px-2 py-3">
                    <div class="nav flex-column nav-pills gap-1">
                        @foreach ($sectionKeys as $sectionKey)
                            @php
                                $definition = $definitions[$sectionKey];
                                $isActive = $sectionKey === $activeSectionKey;
                                $saved = $sections->has($sectionKey);
                                $isSpecial = ($definition['type'] ?? '') === 'enterprise_verification';
                            @endphp
                            <button
                                type="button"
                                wire:click="switchSection('{{ $sectionKey }}')"
                                class="nav-link text-start d-flex align-items-center justify-content-between py-2.5 px-3 {{ $isActive ? 'active bg-primary text-white' : 'text-dark bg-transparent' }}"
                                style="font-size: 0.875rem; border-radius: 6px; transition: all 0.2s;"
                            >
                                <span class="text-truncate me-2">
                                    @if ($isSpecial)<i class="bi bi-building-check me-1 opacity-75"></i>@endif
                                    {{ $definition['label'] }}
                                </span>
                                @if ($saved && !$isSpecial)
                                    <span class="badge rounded-pill {{ $isActive ? 'bg-white text-primary' : 'bg-success-subtle text-success' }} d-inline-flex align-items-center justify-content-center px-1.5 py-1">
                                        <i class="bi bi-check-lg" style="font-size: 0.75rem;"></i>
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column: Form Workspace -->
        <div class="col-12 col-md-8 col-lg-6 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark">{{ $definitions[$activeSectionKey]['label'] ?? 'Section Details' }}</h5>
                    @if ($report->status === 'submitted')
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">Read-only (Submitted)</span>
                    @endif
                </div>
                <div class="card-body p-4">

                    @php $sectionType = $activeSectionDef['type'] ?? 'standard'; @endphp

                    {{-- ── ENTERPRISE VERIFICATION SECTION ── --}}
                    @if ($sectionType === 'enterprise_verification')
                        <div class="mb-3">
                            <p class="text-muted small mb-3">
                                Set verification dates for each enterprise's production status. Changes are written directly to the enterprise's production section.
                            </p>

                            @if (count($enterpriseVerificationData) === 0)
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-building-x d-block mb-2 fs-3 text-secondary"></i>
                                    No enterprise reports found.
                                </div>
                            @else
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                        <thead class="table-light border-bottom">
                                            <tr>
                                                <th class="fw-semibold text-muted py-2.5 px-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em;">Enterprise</th>
                                                <th class="fw-semibold text-muted py-2.5 px-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em;">Period</th>
                                                <th class="fw-semibold text-muted py-2.5 px-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em;">Latest FY</th>
                                                <th class="fw-semibold text-muted py-2.5 px-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em;">Production Status</th>
                                                <th class="fw-semibold text-muted py-2.5 px-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em;">Verification Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($enterpriseVerificationData as $row)
                                                @php
                                                    $isNonProd = $row['latest_production_status'] === 'Non-Production';
                                                    $rowClass = $isNonProd ? 'table-warning' : '';
                                                @endphp
                                                <tr class="{{ $rowClass }}" wire:key="ev-{{ $row['report_id'] }}">
                                                    <td class="px-3 py-2 fw-semibold">{{ $row['enterprise_name'] }}</td>
                                                    <td class="px-3 py-2 text-muted small">
                                                        {{ $row['period'] }}
                                                        <span class="badge {{ $row['status'] === 'submitted' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} ms-1" style="font-size: 0.7rem;">{{ $row['status'] }}</span>
                                                    </td>
                                                    <td class="px-3 py-2 text-muted small">{{ $row['latest_fiscal_year'] }}</td>
                                                    <td class="px-3 py-2">
                                                        @if ($isNonProd)
                                                            <span class="badge bg-warning text-dark" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Non-Production</span>
                                                        @elseif ($row['latest_production_status'] === 'Production Started')
                                                            <span class="badge bg-success-subtle text-success" style="font-size: 0.75rem;"><i class="bi bi-check-circle me-1"></i>Production Started</span>
                                                        @else
                                                            <span class="text-muted small">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="date"
                                                            wire:model="data.verification_dates.{{ $row['report_id'] }}"
                                                            class="form-control form-control-sm"
                                                            style="min-width: 140px;"
                                                            value="{{ $row['developer_verification_date'] }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-semibold" style="font-size: 0.9rem;" wire:click="saveEnterpriseVerification" wire:loading.attr="disabled">
                                        <span wire:loading.remove><i class="bi bi-save2 me-2"></i>Save Verification Dates</span>
                                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                    {{-- ── DUAL COLUMN SECTION (Tax Exemptions) ── --}}
                    @elseif ($sectionType === 'dual_column')
                        @php
                            $columnLabels = $activeSectionDef['column_labels'] ?? ['Column 1', 'Column 2'];
                            $devFields = collect($activeFields)->where('group', 'developer')->values();
                            $entFields = collect($activeFields)->where('group', 'enterprise')->values();
                            $isReadonly = $report->status === 'submitted' || !auth()->user()->can('report-edit');
                        @endphp

                        {{-- Recalculate button --}}
                        @if (!$isReadonly)
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3 fw-semibold" style="font-size: 0.8rem;" wire:click="recalculateFromEnterprises" wire:loading.attr="disabled">
                                    <i class="bi bi-arrow-repeat me-1"></i>Recalculate from Enterprises
                                </button>
                                <span class="text-muted small">Enterprise column values are auto-summed from submitted enterprise reports.</span>
                            </div>
                        @endif

                        <form wire:submit="saveSection">
                            <div class="row g-0">
                                {{-- Developer column --}}
                                <div class="col-12 col-md-6 pe-md-3">
                                    <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom">
                                        <i class="bi bi-building me-1"></i>{{ $columnLabels[0] }}
                                    </h6>
                                    @foreach ($devFields as $field)
                                        @php
                                            $fn = $field['name'];
                                            $fv = $this->data[$fn] ?? '';
                                        @endphp
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-secondary small mb-1">{{ $field['label'] }}</label>
                                            @if ($field['type'] === 'select')
                                                <select wire:model="data.{{ $fn }}" class="form-select form-select-sm @error('data.'.$fn) is-invalid @enderror" {{ $isReadonly ? 'disabled' : '' }}>
                                                    <option value="">Select</option>
                                                    @foreach ($field['options'] ?? [] as $opt)
                                                        <option value="{{ $opt }}" @selected((string)$fv === $opt)>{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif ($field['type'] === 'date')
                                                <input type="date" wire:model="data.{{ $fn }}" class="form-control form-control-sm @error('data.'.$fn) is-invalid @enderror" {{ $isReadonly ? 'disabled' : '' }}>
                                            @else
                                                <input type="number" wire:model="data.{{ $fn }}" class="form-control form-control-sm @error('data.'.$fn) is-invalid @enderror" step="{{ $field['step'] ?? '0.01' }}" {{ $isReadonly ? 'disabled' : '' }}>
                                            @endif
                                            @error('data.'.$fn)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>
                                    @endforeach
                                </div>
                                {{-- Enterprise column --}}
                                <div class="col-12 col-md-6 ps-md-3 mt-4 mt-md-0">
                                    <h6 class="fw-bold text-success mb-3 pb-2 border-bottom">
                                        <i class="bi bi-buildings me-1"></i>{{ $columnLabels[1] }}
                                        <span class="badge bg-success-subtle text-success ms-1 fw-normal" style="font-size: 0.7rem;">auto-calc</span>
                                    </h6>
                                    @foreach ($entFields as $field)
                                        @php
                                            $fn = $field['name'];
                                            $fv = $this->data[$fn] ?? '';
                                            $isAutoCalc = !empty($field['auto_calc']);
                                        @endphp
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-secondary small mb-1">
                                                {{ $field['label'] }}
                                                @if ($isAutoCalc)
                                                    <span class="badge bg-info-subtle text-info ms-1 fw-normal" style="font-size: 0.65rem;">auto</span>
                                                @endif
                                            </label>
                                            @if ($field['type'] === 'select')
                                                <select wire:model="data.{{ $fn }}" class="form-select form-select-sm @error('data.'.$fn) is-invalid @enderror" {{ $isReadonly ? 'disabled' : '' }}>
                                                    <option value="">Select</option>
                                                    @foreach ($field['options'] ?? [] as $opt)
                                                        <option value="{{ $opt }}" @selected((string)$fv === $opt)>{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif ($field['type'] === 'date')
                                                <input type="date" wire:model="data.{{ $fn }}" class="form-control form-control-sm @error('data.'.$fn) is-invalid @enderror" {{ $isReadonly ? 'disabled' : '' }}>
                                            @else
                                                <input type="number" wire:model="data.{{ $fn }}" class="form-control form-control-sm @error('data.'.$fn) is-invalid @enderror" step="{{ $field['step'] ?? '0.01' }}" {{ $isReadonly ? 'disabled' : '' }}>
                                            @endif
                                            @error('data.'.$fn)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if (!$isReadonly)
                                <div class="col-12 border-top pt-3 mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold" style="font-size: 0.9rem;" wire:loading.attr="disabled">
                                        <span wire:loading.remove><i class="bi bi-save2 me-2"></i>Save & Progress</span>
                                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving Changes...</span>
                                    </button>
                                </div>
                            @endif
                        </form>

                    {{-- ── STANDARD SECTION ── --}}
                    @elseif ($definitions[$activeSectionKey] ?? null)
                        @php
                            $isReadonly = $report->status === 'submitted' || !auth()->user()->can('report-edit');
                            $hasAutoCalc = collect($activeFields)->contains(fn ($f) => !empty($f['auto_calc']));
                        @endphp

                        {{-- Recalculate button for sections with auto-calc fields --}}
                        @if ($hasAutoCalc && !$isReadonly)
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3 fw-semibold" style="font-size: 0.8rem;" wire:click="recalculateFromEnterprises" wire:loading.attr="disabled">
                                    <i class="bi bi-arrow-repeat me-1"></i>Recalculate from Enterprises
                                </button>
                                <span class="text-muted small">Fields marked <span class="badge bg-info-subtle text-info" style="font-size: 0.7rem;">auto</span> are pre-filled from submitted enterprise reports.</span>
                            </div>
                        @endif

                        <form wire:submit="saveSection" class="row g-3">
                            @php $activePayload = $sections[$activeSectionKey]->payload ?? []; @endphp

                            @foreach ($activeFields as $field)
                                @php
                                    $fieldName = $field['name'];
                                    $fieldType = $field['type'];
                                    $fieldValue = $this->data[$fieldName] ?? $activePayload[$fieldName] ?? '';
                                    $isDeveloperOnly = !empty($field['developer_only']);
                                    $isFieldReadonly = $isReadonly || ($isDeveloperOnly && $report->audience === 'enterprise');
                                    $isAutoCalc = !empty($field['auto_calc']);
                                    $colWidth = in_array($fieldType, ['table', 'textarea']) ? 'col-12' : 'col-md-6 col-12';
                                @endphp

                                <div class="{{ $colWidth }} mb-2">
                                    @if ($fieldType !== 'table')
                                        <label for="field_{{ $fieldName }}" class="form-label fw-semibold text-secondary small mb-1">
                                            {{ $field['label'] }}
                                            @if ($isAutoCalc)
                                                <span class="badge bg-info-subtle text-info ms-1 fw-normal" style="font-size: 0.65rem;">auto</span>
                                            @endif
                                            @if ($isDeveloperOnly)
                                                <span class="badge bg-primary-subtle text-primary ms-1 fw-normal" style="font-size: 0.65rem;">by developer</span>
                                            @endif
                                        </label>
                                    @endif

                                    @if ($fieldType === 'table')
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-secondary small mb-2 d-block">{{ $field['label'] }}</label>
                                            <div class="table-responsive border rounded-3">
                                                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                                    <thead class="table-light border-bottom">
                                                        <tr>
                                                            @foreach ($field['columns'] as $col)
                                                                <th class="fw-semibold text-muted py-2.5 px-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap;">{{ $col['label'] }}</th>
                                                            @endforeach
                                                            @if (!$isFieldReadonly)
                                                                <th style="width: 50px;"></th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody class="border-top-0">
                                                        @php $rows = is_array($fieldValue) ? $fieldValue : []; @endphp
                                                        @forelse ($rows as $index => $row)
                                                            @php
                                                                $isNonProduction = ($row['production_status'] ?? '') === 'Non-Production';
                                                                $rowHighlight = $isNonProduction ? 'table-warning' : '';
                                                            @endphp
                                                            <tr class="{{ $rowHighlight }}" wire:key="row-{{ $fieldName }}-{{ $index }}">
                                                                @foreach ($field['columns'] as $col)
                                                                    <td class="p-1 px-2">
                                                                        @if (($col['type'] ?? 'text') === 'select')
                                                                            <select
                                                                                wire:model.live="data.{{ $fieldName }}.{{ $index }}.{{ $col['name'] }}"
                                                                                class="form-select form-select-sm border-0 bg-light"
                                                                                style="border-radius: 4px; min-width: 140px;"
                                                                                {{ $isFieldReadonly ? 'disabled' : '' }}>
                                                                                <option value="">Select</option>
                                                                                @foreach ($col['options'] ?? [] as $opt)
                                                                                    <option value="{{ $opt }}" @selected(($row[$col['name']] ?? '') === $opt)>{{ $opt }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @elseif (($col['type'] ?? 'text') === 'number')
                                                                            <input type="number"
                                                                                wire:model="data.{{ $fieldName }}.{{ $index }}.{{ $col['name'] }}"
                                                                                class="form-control form-control-sm border-0 bg-light"
                                                                                style="border-radius: 4px; min-width: 110px;"
                                                                                step="0.01"
                                                                                {{ $isFieldReadonly ? 'disabled' : '' }}>
                                                                        @else
                                                                            <input type="text"
                                                                                wire:model="data.{{ $fieldName }}.{{ $index }}.{{ $col['name'] }}"
                                                                                class="form-control form-control-sm border-0 bg-light"
                                                                                style="border-radius: 4px; min-width: 110px;"
                                                                                placeholder="{{ strtolower($col['label']) }}"
                                                                                {{ $isFieldReadonly ? 'disabled' : '' }}>
                                                                        @endif
                                                                    </td>
                                                                @endforeach
                                                                @if (!$isFieldReadonly)
                                                                    <td class="text-end px-2">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-link text-danger p-1"
                                                                            wire:click="removeTableRow('{{ $fieldName }}', {{ $index }})"
                                                                            title="Remove Row">
                                                                            <i class="bi bi-trash-fill" style="font-size: 0.95rem;"></i>
                                                                        </button>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="{{ count($field['columns']) + ($isFieldReadonly ? 0 : 1) }}" class="text-muted text-center py-4 small bg-light-subtle">
                                                                    <i class="bi bi-folder-x me-1 fs-5 d-block text-secondary mb-1"></i>
                                                                    No rows added yet. Click "Add Row" to begin.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if (!$isFieldReadonly)
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary py-1.5 px-3 fw-semibold" style="font-size: 0.8rem;" wire:click="addTableRow('{{ $fieldName }}')">
                                                        <i class="bi bi-plus-lg me-1"></i>Add Row
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                    @elseif ($fieldType === 'textarea')
                                        <textarea
                                            id="field_{{ $fieldName }}"
                                            wire:model="data.{{ $fieldName }}"
                                            rows="3"
                                            class="form-control @error('data.'.$fieldName) is-invalid @enderror"
                                            style="border-radius: 6px; font-size: 0.9rem;"
                                            placeholder="Write description here..."
                                            {{ $isFieldReadonly ? 'disabled' : '' }}>{{ $fieldValue }}</textarea>
                                        @error('data.'.$fieldName)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                    @elseif ($fieldType === 'select')
                                        <select
                                            id="field_{{ $fieldName }}"
                                            wire:model="data.{{ $fieldName }}"
                                            class="form-select @error('data.'.$fieldName) is-invalid @enderror"
                                            style="border-radius: 6px; font-size: 0.9rem;"
                                            {{ $isFieldReadonly ? 'disabled' : '' }}>
                                            <option value="">Select option</option>
                                            @foreach (($field['options'] ?? []) as $option)
                                                <option value="{{ $option }}" @selected((string) $fieldValue === (string) $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @error('data.'.$fieldName)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                    @else
                                        <input
                                            id="field_{{ $fieldName }}"
                                            type="{{ $fieldType === 'number' ? 'number' : ($fieldType === 'date' ? 'date' : 'text') }}"
                                            wire:model="data.{{ $fieldName }}"
                                            class="form-control @error('data.'.$fieldName) is-invalid @enderror"
                                            style="border-radius: 6px; font-size: 0.9rem;"
                                            @if ($fieldType === 'number' && isset($field['step'])) step="{{ $field['step'] }}" @endif
                                            {{ $isFieldReadonly ? 'disabled' : '' }}>
                                        @error('data.'.$fieldName)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>
                            @endforeach

                            <!-- Form Actions -->
                            @if ($report->status !== 'submitted' && auth()->user()->can('report-edit'))
                                <div class="col-12 border-top pt-3 mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold" style="font-size: 0.9rem;" wire:loading.attr="disabled">
                                        <span wire:loading.remove><i class="bi bi-save2 me-2"></i>Save & Progress</span>
                                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving Changes...</span>
                                    </button>
                                </div>
                            @endif
                        </form>

                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No active section has been configured.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Right Sidebar: Activity Logs Timeline -->
        <div class="col-12 col-md-12 col-lg-3 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3 pb-2">
                    <h6 class="fw-bold text-uppercase text-muted tracking-wider mb-0" style="font-size: 0.75rem;">Activity History</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <div class="position-relative">
                        @forelse ($events as $event)
                            <div class="position-relative ps-3 pb-3 border-start border-light-subtle" style="font-size: 0.85rem;">
                                <span class="position-absolute start-0 translate-middle bg-primary border border-white rounded-circle"
                                      style="width: 10px; height: 10px; top: 6px; left: 0px;"></span>
                                <div class="fw-bold text-dark text-capitalize" style="font-size: 0.8rem;">
                                    {{ str_replace('_', ' ', $event->event_type) }}
                                </div>
                                <div class="text-muted mt-0.5" style="font-size: 0.75rem; line-height: 1.35;">{{ $event->message }}</div>
                                <div class="text-black-50 small mt-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-person me-1"></i>{{ $event->user?->name ?? 'System' }}<br>
                                    <i class="bi bi-clock me-1"></i>{{ $event->created_at?->format('d-M-Y H:i') }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center mb-0 small py-4">
                                <i class="bi bi-clock-history d-block mb-1 text-secondary fs-5"></i>
                                No system logs available yet.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
