@extends('layouts.admin')

@section('title', 'Report Workspace')

@php
    $activeDefinition = $definitions[$activeSectionKey] ?? null;
    $activePayload = $sections[$activeSectionKey]->payload ?? [];
@endphp

@section('page-content')
    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-semibold mb-1" style="color:#181C32;">{{ ucfirst($report->audience) }} Report - {{ $report->periodLabel() }}</h5>
                <div class="text-muted small">Type: {{ ucfirst($report->report_type) }} | Year: {{ $report->report_year }}</div>
            </div>
            <div class="d-flex align-items-center gap-2">
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
                @if ($report->status !== 'submitted' && auth()->user()->can('report-submit'))
                    <form method="POST" action="{{ route('reports.submit', $report) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Submit Report</button>
                    </form>
                @endif
                <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm">Back</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sections</h5>
                </div>
                <div class="card-body p-2">
                    <div class="d-grid gap-2">
                        @foreach ($sectionKeys as $sectionKey)
                            @php
                                $definition = $definitions[$sectionKey];
                                $isActive = $sectionKey === $activeSectionKey;
                                $saved = $sections->has($sectionKey);
                            @endphp
                            <a
                                href="{{ route('reports.show', ['report' => $report, 'section' => $sectionKey]) }}"
                                class="btn text-start {{ $isActive ? 'btn-primary' : 'btn-light' }}"
                            >
                                {{ $definition['label'] }}
                                @if ($saved)
                                    <span class="float-end text-success">Saved</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $activeDefinition['label'] ?? 'Section' }}</h5>
                    @if ($report->status === 'submitted')
                        <span class="badge text-bg-warning">Read-only after submit</span>
                    @endif
                </div>
                <div class="card-body">
                    @if ($activeDefinition)
                        <form method="POST" action="{{ route('reports.sections.update', ['report' => $report, 'sectionKey' => $activeSectionKey]) }}" class="row g-3">
                            @csrf
                            @method('PUT')

                                @foreach ($activeFields as $field)
                                    @php
                                        $fieldName = $field['name'];
                                        $fieldType = $field['type'];
                                        $fieldValue = old('data.'.$fieldName, $activePayload[$fieldName] ?? '');
                                    @endphp

                                    @if ($fieldType === 'table')
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">{{ $field['label'] }}</label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm" id="table_{{ $fieldName }}">
                                                    <thead>
                                                        <tr>
                                                            @foreach ($field['columns'] as $col)
                                                                <th>{{ $col['label'] }}</th>
                                                            @endforeach
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body_{{ $fieldName }}">
                                                        @php
                                                            $rows = is_array($fieldValue) ? $fieldValue : [[]];
                                                        @endphp
                                                        @foreach ($rows as $index => $row)
                                                            <tr>
                                                                @foreach ($field['columns'] as $col)
                                                                    <td>
                                                                        <input type="text" name="data[{{ $fieldName }}][{{ $index }}][{{ $col['name'] }}]" value="{{ $row[$col['name']] ?? '' }}" class="form-control form-control-sm">
                                                                    </td>
                                                                @endforeach
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Remove</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="addRow('{{ $fieldName }}', {{ json_encode($field['columns']) }})">Add Row</button>
                                            </div>
                                        </div>
                                    @elseif ($fieldType === 'textarea')
                                        <div class="col-12">
                                            <label for="field_{{ $fieldName }}" class="form-label">{{ $field['label'] }}</label>
                                            <textarea
                                                id="field_{{ $fieldName }}"
                                                name="data[{{ $fieldName }}]"
                                                rows="3"
                                                class="form-control @error('data.'.$fieldName) is-invalid @enderror"
                                                {{ $report->status === 'submitted' || ! auth()->user()->can('report-edit') ? 'disabled' : '' }}
                                            >{{ $fieldValue }}</textarea>
                                            @error('data.'.$fieldName)
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @elseif ($fieldType === 'select')
                                        <div class="col-12">
                                            <label for="field_{{ $fieldName }}" class="form-label">{{ $field['label'] }}</label>
                                            <select
                                                id="field_{{ $fieldName }}"
                                                name="data[{{ $fieldName }}]"
                                                class="form-select @error('data.'.$fieldName) is-invalid @enderror"
                                                {{ $report->status === 'submitted' || ! auth()->user()->can('report-edit') ? 'disabled' : '' }}
                                            >
                                                <option value="">Select option</option>
                                                @foreach (($field['options'] ?? []) as $option)
                                                    <option value="{{ $option }}" @selected((string) $fieldValue === (string) $option)>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @error('data.'.$fieldName)
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @else
                                        <div class="col-12">
                                            <label for="field_{{ $fieldName }}" class="form-label">{{ $field['label'] }}</label>
                                            <input
                                                id="field_{{ $fieldName }}"
                                                type="{{ $fieldType === 'number' ? 'number' : ($fieldType === 'date' ? 'date' : 'text') }}"
                                                name="data[{{ $fieldName }}]"
                                                value="{{ $fieldValue }}"
                                                class="form-control @error('data.'.$fieldName) is-invalid @enderror"
                                                @if ($fieldType === 'number' && isset($field['step'])) step="{{ $field['step'] }}" @endif
                                                {{ $report->status === 'submitted' || ! auth()->user()->can('report-edit') ? 'disabled' : '' }}
                                            >
                                            @error('data.'.$fieldName)
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach

                                @if ($report->status !== 'submitted' && auth()->user()->can('report-edit'))
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Section</button>
                                    </div>
                                @endif
                    @else
                        <p class="text-muted mb-0">No section available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Activity Logs</h5>
                </div>
                <div class="card-body">
                    @forelse ($events as $event)
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="fw-semibold small text-capitalize">{{ str_replace('_', ' ', $event->event_type) }}</div>
                            <div class="small text-muted">{{ $event->message }}</div>
                            <div class="small text-muted">{{ $event->created_at?->format('d-M-Y H:i') }} by {{ $event->user?->name }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
function addRow(fieldName, columns) {
    const tbody = document.getElementById('body_' + fieldName);
    const rowCount = tbody.querySelectorAll('tr').length;
    const tr = document.createElement('tr');
    columns.forEach(col => {
        const td = document.createElement('td');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'data[' + fieldName + '][' + rowCount + '][' + col.name + ']';
        input.className = 'form-control form-control-sm';
        td.appendChild(input);
        tr.appendChild(td);
    });
    const actionTd = document.createElement('td');
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-sm btn-danger';
    removeBtn.textContent = 'Remove';
    removeBtn.onclick = function() { this.closest('tr').remove(); };
    actionTd.appendChild(removeBtn);
    tr.appendChild(actionTd);
    tbody.appendChild(tr);
}
</script>
@endpush
@endsection
