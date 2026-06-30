<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\ReportSection;
use App\Models\User;
use App\Support\ReportBlueprint;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ReportWorkspace extends Component
{
    public Report $report;
    public string $activeSectionKey = '';
    public array $data = [];
    public bool $ownershipError = false;
    public array $enterpriseVerificationData = [];

    public function mount(Report $report, ?string $section = null)
    {
        if ($report->user_id !== auth()->id()) {
            $this->ownershipError = true;
            return;
        }

        $this->report = $report;
        $sectionKeys = ReportBlueprint::sectionKeysForAudience($report->audience);

        $this->activeSectionKey = $section && in_array($section, $sectionKeys, true)
            ? $section
            : ($sectionKeys[0] ?? '');

        $this->loadSectionData();
    }

    public function switchSection(string $sectionKey): void
    {
        $sectionKeys = ReportBlueprint::sectionKeysForAudience($this->report->audience);
        if (!in_array($sectionKey, $sectionKeys, true)) {
            return;
        }

        $this->activeSectionKey = $sectionKey;
        $this->loadSectionData();
    }

    public function loadSectionData(): void
    {
        if ($this->activeSectionKey === 'developer_enterprise_verification') {
            $this->loadEnterpriseVerificationData();
            $this->data = [];
            return;
        }

        $section = $this->report->sections()->where('section_key', $this->activeSectionKey)->first();
        $this->data = $section?->payload ?? [];

        if ($this->report->audience === Report::AUDIENCE_DEVELOPER) {
            $this->applyEnterpriseAggregates();
        }
    }

    protected function applyEnterpriseAggregates(): void
    {
        $calcSections = ['developer_investment', 'developer_tax_exemptions', 'developer_production'];
        if (!in_array($this->activeSectionKey, $calcSections, true)) {
            return;
        }

        foreach ($this->computeEnterpriseAggregates($this->activeSectionKey) as $fieldName => $value) {
            if (!isset($this->data[$fieldName]) || $this->data[$fieldName] === '' || $this->data[$fieldName] === null) {
                $this->data[$fieldName] = $value;
            }
        }
    }

    public function recalculateFromEnterprises(): void
    {
        if ($this->ownershipError || $this->report->user_id !== auth()->id()) {
            return;
        }

        foreach ($this->computeEnterpriseAggregates($this->activeSectionKey) as $fieldName => $value) {
            $this->data[$fieldName] = $value;
        }

        $this->dispatch('notify', type: 'info', message: 'Values recalculated from enterprise reports.');
    }

    protected function computeEnterpriseAggregates(string $sectionKey): array
    {
        $enterpriseReportIds = Report::whereIn(
            'user_id',
            User::where('developer_type', 2)->select('id')
        )->where('status', Report::STATUS_SUBMITTED)->pluck('id');

        if ($enterpriseReportIds->isEmpty()) {
            return [];
        }

        $aggregates = [];

        if ($sectionKey === 'developer_investment') {
            $detailsSections = ReportSection::whereIn('report_id', $enterpriseReportIds)
                ->where('section_key', 'enterprise_details')
                ->get();

            $investmentSections = ReportSection::whereIn('report_id', $enterpriseReportIds)
                ->where('section_key', 'enterprise_investment')
                ->get()->keyBy('report_id');

            $localCount = 0;
            $foreignCount = 0;
            $localInvestment = 0.0;
            $foreignInvestment = 0.0;

            foreach ($detailsSections as $ds) {
                $country = strtolower(trim($ds->payload['country_of_origin'] ?? ''));
                $isLocal = $country === '' || $country === 'pakistan';
                $amount = (float) ($investmentSections[$ds->report_id]?->payload['investment_till_date_mn'] ?? 0);

                if ($isLocal) {
                    $localCount++;
                    $localInvestment += $amount;
                } else {
                    $foreignCount++;
                    $foreignInvestment += $amount;
                }
            }

            $aggregates['local_units'] = $localCount;
            $aggregates['foreign_units'] = $foreignCount;
            $aggregates['local_investment_bn'] = round($localInvestment / 1000, 4);
            $aggregates['foreign_investment_bn'] = round($foreignInvestment / 1000, 4);
        }

        if ($sectionKey === 'developer_tax_exemptions') {
            $customDutySum = 0.0;
            $incomeTaxSum = 0.0;

            foreach (
                ReportSection::whereIn('report_id', $enterpriseReportIds)
                    ->where('section_key', 'enterprise_tax_exemptions')
                    ->get() as $ts
            ) {
                $customDutySum += (float) ($ts->payload['custom_duty_amount_mn'] ?? 0);
                $incomeTaxSum += (float) ($ts->payload['income_tax_amount_mn'] ?? 0);
            }

            $aggregates['ent_custom_duty_amount_mn'] = round($customDutySum, 2);
            $aggregates['ent_income_tax_amount_mn'] = round($incomeTaxSum, 2);
        }

        if ($sectionKey === 'developer_production') {
            $inProduction = 0;
            $inConstruction = 0;

            foreach (
                ReportSection::whereIn('report_id', $enterpriseReportIds)
                    ->where('section_key', 'enterprise_production')
                    ->get() as $ps
            ) {
                $latestRow = collect($ps->payload['fiscal_years'] ?? [])->last();
                if ($latestRow && ($latestRow['production_status'] ?? '') === 'Production Started') {
                    $inProduction++;
                } else {
                    $inConstruction++;
                }
            }

            $aggregates['total_companies_production'] = $inProduction;
            $aggregates['total_companies_construction'] = $inConstruction;
        }

        return $aggregates;
    }

    protected function loadEnterpriseVerificationData(): void
    {
        $enterpriseReports = Report::whereIn(
            'user_id',
            User::where('developer_type', 2)->select('id')
        )->with(['user', 'sections' => fn ($q) => $q->whereIn('section_key', ['enterprise_details', 'enterprise_production'])])
            ->latest('id')
            ->get();

        $this->enterpriseVerificationData = $enterpriseReports->map(function (Report $r) {
            $sections = $r->sections->keyBy('section_key');
            $prodPayload = $sections['enterprise_production']?->payload ?? [];
            $latestFy = collect($prodPayload['fiscal_years'] ?? [])->last();

            return [
                'report_id' => $r->id,
                'period' => $r->periodLabel(),
                'status' => $r->status,
                'enterprise_name' => $sections['enterprise_details']?->payload['enterprise_name'] ?? $r->user?->name ?? '—',
                'latest_fiscal_year' => $latestFy['fiscal_year'] ?? '—',
                'latest_production_status' => $latestFy['production_status'] ?? '—',
                'production_commencement_date' => $prodPayload['production_commencement_date'] ?? '',
                'developer_verification_date' => $prodPayload['developer_verification_date'] ?? '',
            ];
        })->values()->toArray();
    }

    public function saveEnterpriseVerification(): void
    {
        if ($this->ownershipError || $this->report->user_id !== auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Unauthorized.');
            return;
        }

        $validEnterpriseReportIds = Report::whereIn(
            'user_id',
            User::where('developer_type', 2)->select('id')
        )->pluck('id')->flip();

        foreach ($this->data['verification_dates'] ?? [] as $reportId => $date) {
            $reportId = (int) $reportId;
            if (!$validEnterpriseReportIds->has($reportId)) {
                continue;
            }

            $section = ReportSection::where('report_id', $reportId)
                ->where('section_key', 'enterprise_production')
                ->first();

            if (!$section) {
                continue;
            }

            $payload = $section->payload;
            $payload['developer_verification_date'] = $date ?: null;
            $section->update(['payload' => $payload]);
        }

        $this->report->events()->create([
            'user_id' => auth()->id(),
            'event_type' => 'enterprise_verification',
            'message' => 'Enterprise production verification dates updated.',
        ]);

        $this->dispatch('notify', type: 'success', message: 'Verification dates saved.');
        $this->loadEnterpriseVerificationData();
    }

    public function addTableRow(string $fieldName): void
    {
        $fields = ReportBlueprint::fieldsForSection($this->activeSectionKey);
        $field = collect($fields)->firstWhere('name', $fieldName);

        if (!$field || ($field['type'] ?? '') !== 'table') {
            return;
        }

        $row = [];
        foreach ($field['columns'] ?? [] as $col) {
            $row[$col['name']] = '';
        }

        $this->data[$fieldName][] = $row;
    }

    public function removeTableRow(string $fieldName, int $index): void
    {
        if (isset($this->data[$fieldName][$index])) {
            unset($this->data[$fieldName][$index]);
            $this->data[$fieldName] = array_values($this->data[$fieldName]);
        }
    }

    public function saveSection(): void
    {
        if ($this->report->status === Report::STATUS_SUBMITTED) {
            $this->dispatch('notify', type: 'error', message: 'Submitted report cannot be edited.');
            return;
        }

        $allowedFields = ReportBlueprint::fieldsForSection($this->activeSectionKey);
        $rules = [];

        foreach ($allowedFields as $field) {
            if ($field['type'] === 'table') {
                foreach ($this->data[$field['name']] ?? [] as $i => $row) {
                    foreach ($field['columns'] ?? [] as $col) {
                        $rules["data.{$field['name']}.{$i}.{$col['name']}"] = ['nullable', 'string'];
                    }
                }
                continue;
            }

            $fieldRules = [!empty($field['required']) ? 'required' : 'nullable'];
            $fieldRules[] = match ($field['type']) {
                'number' => 'numeric',
                'date' => 'date',
                default => 'string',
            };
            $rules['data.' . $field['name']] = $fieldRules;
        }

        $this->validate($rules);

        $this->report->sections()->updateOrCreate(
            ['section_key' => $this->activeSectionKey],
            ['payload' => $this->data],
        );

        $this->report->events()->create([
            'user_id' => auth()->id(),
            'event_type' => 'section_saved',
            'message' => sprintf('Section "%s" saved as draft.', str_replace('_', ' ', $this->activeSectionKey)),
        ]);

        $this->dispatch('notify', type: 'success', message: 'Section saved successfully.');
    }

    public function submit(): void
    {
        if ($this->ownershipError || $this->report->user_id !== auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Unauthorized.');
            return;
        }

        if ($this->report->status === Report::STATUS_SUBMITTED) {
            $this->dispatch('notify', type: 'info', message: 'Report already submitted.');
            return;
        }

        // enterprise_verification is optional, not required for submission
        $requiredSections = array_values(array_filter(
            ReportBlueprint::sectionKeysForAudience($this->report->audience),
            fn (string $k) => $k !== 'developer_enterprise_verification'
        ));
        $savedSections = $this->report->sections()->pluck('section_key')->all();
        $missingSections = array_values(array_diff($requiredSections, $savedSections));

        if ($missingSections !== []) {
            $firstMissing = $missingSections[0];
            $this->activeSectionKey = $firstMissing;
            $this->loadSectionData();

            $names = implode(', ', array_map(fn (string $s): string => str_replace('_', ' ', $s), $missingSections));
            $this->dispatch('notify', type: 'error', message: 'Complete all sections before submission. Missing: ' . $names . '.');
            return;
        }

        $this->report->update(['status' => Report::STATUS_SUBMITTED]);

        $this->report->events()->create([
            'user_id' => auth()->id(),
            'event_type' => 'submitted',
            'message' => 'Report submitted for review.',
        ]);

        $this->dispatch('notify', type: 'success', message: 'Report submitted successfully.');
    }

    public function render()
    {
        if ($this->ownershipError) {
            abort(403);
        }

        $definitions = ReportBlueprint::sectionDefinitions();
        $sectionKeys = ReportBlueprint::sectionKeysForAudience($this->report->audience);
        $events = $this->report->events()->with('user')->take(10)->get();
        $sections = $this->report->sections()->get()->keyBy('section_key');
        $activeSectionDef = $definitions[$this->activeSectionKey] ?? [];

        return view('livewire.report-workspace', [
            'report' => $this->report,
            'definitions' => $definitions,
            'sectionKeys' => $sectionKeys,
            'activeSectionKey' => $this->activeSectionKey,
            'activeSectionDef' => $activeSectionDef,
            'activeFields' => ReportBlueprint::fieldsForSection($this->activeSectionKey),
            'sections' => $sections,
            'events' => $events,
            'enterpriseVerificationData' => $this->enterpriseVerificationData,
        ]);
    }
}
