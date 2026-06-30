<?php

namespace App\Livewire;

use App\Models\Report;
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
        $section = $this->report->sections()->where('section_key', $this->activeSectionKey)->first();
        $this->data = $section?->payload ?? [];
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
        if ($this->ownershipError || $this->report->user_id !== auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Unauthorized.');
            return;
        }

        if ($this->report->status === Report::STATUS_SUBMITTED) {
            $this->dispatch('notify', type: 'error', message: 'Submitted report cannot be edited.');
            return;
        }

        $allowedFields = ReportBlueprint::fieldsForSection($this->activeSectionKey);
        $rules = [];

        foreach ($allowedFields as $field) {
            $fieldRules = [];
            if (!empty($field['required'])) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
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

        $requiredSections = ReportBlueprint::sectionKeysForAudience($this->report->audience);
        $savedSections = $this->report->sections()->pluck('section_key')->all();
        $missingSections = array_values(array_diff($requiredSections, $savedSections));


        if ($missingSections !== []) {
            $firstMissing = $missingSections[0];
            $this->activeSectionKey = $firstMissing;
            $this->loadSectionData();

            $names = implode(', ', array_map(fn(string $s): string => str_replace('_', ' ', $s), $missingSections));
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

        return view('livewire.report-workspace', [
            'report' => $this->report,
            'definitions' => $definitions,
            'sectionKeys' => $sectionKeys,
            'activeSectionKey' => $this->activeSectionKey,
            'activeFields' => ReportBlueprint::fieldsForSection($this->activeSectionKey),
            'sections' => $sections,
            'events' => $events,
        ]);
    }
}
