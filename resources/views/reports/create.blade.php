@extends('layouts.admin')

@section('title', 'New Report')

@section('page-content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Report</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('reports.store') }}">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Audience <span class="text-danger">*</span></label>
                        <select name="audience" class="form-select @error('audience') is-invalid @enderror" required>
                            <option value="">Select audience...</option>
                            @if (auth()->user()->developer_type == 1)
                                <option value="developer" @selected(old('audience') === '1') selected>Zone Developer</option>
                            @else
                                <option value="enterprise" @selected(old('audience') === '2') selected>Zone Enterprise</option>
                            @endif
                        </select>
                        @error('audience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Report Type <span class="text-danger">*</span></label>
                        <select name="report_type" id="report_type" class="form-select @error('report_type') is-invalid @enderror" required>
                            <option value="">Select period...</option>
                            <option value="monthly" @selected(old('report_type') === 'monthly')>Monthly</option>
                            <option value="quarterly" @selected(old('report_type') === 'quarterly')>Quarterly</option>
                            <option value="biannual" @selected(old('report_type') === 'biannual')>Biannual (6-month)</option>
                            <option value="annual" @selected(old('report_type') === 'annual')>Annual</option>
                        </select>
                        @error('report_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4" id="month_field" style="display:none;">
                        <label class="form-label">Month <span class="text-danger">*</span></label>
                        <select name="report_month" class="form-select @error('report_month') is-invalid @enderror">
                            <option value="">Select month...</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" @selected(old('report_month') == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endforeach
                        </select>
                        @error('report_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4" id="quarter_field" style="display:none;">
                        <label class="form-label">Quarter <span class="text-danger">*</span></label>
                        <select name="report_quarter" class="form-select @error('report_quarter') is-invalid @enderror">
                            <option value="">Select quarter...</option>
                            @foreach (range(1, 4) as $q)
                                <option value="{{ $q }}" @selected(old('report_quarter') == $q)>Q{{ $q }}</option>
                            @endforeach
                        </select>
                        @error('report_quarter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4" id="half_field" style="display:none;">
                        <label class="form-label">Half <span class="text-danger">*</span></label>
                        <select name="biannual_half" class="form-select @error('biannual_half') is-invalid @enderror">
                            <option value="">Select half...</option>
                            <option value="1" @selected(old('biannual_half') == 1)>First Half (Jan-Jun)</option>
                            <option value="2" @selected(old('biannual_half') == 2)>Second Half (Jul-Dec)</option>
                        </select>
                        @error('biannual_half') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <select name="report_year" class="form-select @error('report_year') is-invalid @enderror" required>
                            <option value="">Select year...</option>
                            @foreach ($years as $y)
                                <option value="{{ $y }}" @selected(old('report_year', now()->year) == $y)>{{ $y }}</option>
                            @endforeach
                        </select>
                        @error('report_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reports.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Create Report</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('report_type')?.addEventListener('change', function() {
    const val = this.value;
    document.getElementById('month_field').style.display = val === 'monthly' ? 'block' : 'none';
    document.getElementById('quarter_field').style.display = val === 'quarterly' ? 'block' : 'none';
    document.getElementById('half_field').style.display = val === 'biannual' ? 'block' : 'none';
});
document.getElementById('report_type')?.dispatchEvent(new Event('change'));
</script>
@endpush
