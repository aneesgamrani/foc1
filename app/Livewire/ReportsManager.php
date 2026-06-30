<?php

namespace App\Livewire;

use App\Models\Report;
use App\Support\ReportBlueprint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportsManager extends Component
{
    public $audience = '';
    public $report_type = '';
    public $report_month = '';
    public $report_quarter = '';
    public $biannual_half = '';
    public $report_year;

    protected function rules(): array
    {
        return [
            'audience' => ['required', 'in:' . Report::AUDIENCE_DEVELOPER . ',' . Report::AUDIENCE_ENTERPRISE],
            'report_type' => ['required', 'in:' . implode(',', Report::TYPES)],
            'report_month' => ['nullable', 'integer', 'between:1,12'],
            'report_quarter' => ['nullable', 'integer', 'between:1,4'],
            'biannual_half' => ['nullable', 'integer', 'between:1,2'],
            'report_year' => ['required', 'integer', 'between:2020,' . ((int) now()->year + 1)],
        ];
    }

    protected $listeners = ['resetCreateForm'];

    public function resetCreateForm()
    {
        $this->resetForm();
    }

    public function mount()
    {
        $this->report_year = now()->year;
    }

    public function render()
    {
        $years = array_reverse(range(2020, (int) now()->year + 1));
        $reports = Report::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.reports-manager', [
            'reports' => $reports,
            'years' => $years,
        ]);
    }

    public function save()
    {
        $this->authorize('report-create');
        $this->validate();

        $validated = [
            'audience' => $this->audience,
            'report_type' => $this->report_type,
            'report_month' => $this->report_type === Report::TYPE_MONTHLY ? $this->report_month : null,
            'report_quarter' => $this->report_type === Report::TYPE_QUARTERLY ? $this->report_quarter : null,
            'biannual_half' => $this->report_type === Report::TYPE_BIANNUAL ? $this->biannual_half : null,
            'report_year' => $this->report_year,
        ];

        $report = Report::create([
            'user_id' => Auth::id(),
            'audience' => $validated['audience'],
            'report_type' => $validated['report_type'],
            'report_month' => $validated['report_month'],
            'report_quarter' => $validated['report_quarter'],
            'biannual_half' => $validated['biannual_half'],
            'report_year' => $validated['report_year'],
            'status' => Report::STATUS_DRAFT,
        ]);

        $report->events()->create([
            'user_id' => Auth::id(),
            'event_type' => 'created',
            'message' => 'Report initialized with period ' . $report->periodLabel() . '.',
        ]);

        $this->resetForm();
        session()->flash('success', 'Report created successfully.');

        $sectionKey = ReportBlueprint::sectionKeysForAudience($report->audience)[0] ?? null;
        if ($sectionKey) {
            return redirect()->route('reports.show', ['report' => $report, 'section' => $sectionKey]);
        }
    }

    private function resetForm(): void
    {
        $this->reset(['audience', 'report_type', 'report_month', 'report_quarter', 'biannual_half']);
        $this->report_year = now()->year;
    }
}
