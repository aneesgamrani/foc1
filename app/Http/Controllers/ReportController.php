<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveReportSectionRequest;
use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Support\ReportBlueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::query()
            ->when(! auth()->user()->can('report-list'), fn ($q) => $q->where('user_id', auth()->id()))
            ->latest('id')
            ->get();

        $years = array_reverse(range(2019, (int) now()->year + 1));

        return view('reports.index', [
            'reports' => $reports,
            'years' => $years,
        ]);
    }

    public function create(): View
    {
        $years = array_reverse(range(2019, (int) now()->year + 1));

        return view('reports.create', ['years' => $years]);
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $report = Report::create([
            'user_id' => $request->user()->id,
            'audience' => $validated['audience'],
            'report_type' => $validated['report_type'],
            'report_month' => $validated['report_type'] === Report::TYPE_MONTHLY ? $validated['report_month'] : null,
            'report_quarter' => $validated['report_type'] === Report::TYPE_QUARTERLY ? $validated['report_quarter'] : null,
            'biannual_half' => $validated['report_type'] === Report::TYPE_BIANNUAL ? $validated['biannual_half'] : null,
            'report_year' => $validated['report_year'],
            'status' => Report::STATUS_DRAFT,
        ]);

        $report->events()->create([
            'user_id' => $request->user()->id,
            'event_type' => 'created',
            'message' => 'Report initialized with period '.$report->periodLabel().'.',
        ]);

        return redirect()->route('reports.show', ['report' => $report, 'section' => ReportBlueprint::sectionKeysForAudience($report->audience)[0] ?? null])
            ->with('success', 'Report period created successfully.');
    }

    public function show(Request $request, Report $report): View
    {
        $this->ensureOwnership($report);

        $definitions = ReportBlueprint::sectionDefinitions();
        $sectionKeys = ReportBlueprint::sectionKeysForAudience($report->audience);

        $activeSectionKey = (string) $request->query('section', $sectionKeys[0] ?? '');

        if (! in_array($activeSectionKey, $sectionKeys, true)) {
            $activeSectionKey = $sectionKeys[0] ?? '';
        }

        $sections = $report->sections()->get()->keyBy('section_key');
        $events = $report->events()->with('user')->take(10)->get();

        return view('reports.show', [
            'report' => $report,
            'definitions' => $definitions,
            'sectionKeys' => $sectionKeys,
            'activeSectionKey' => $activeSectionKey,
            'activeFields' => ReportBlueprint::fieldsForSection($activeSectionKey),
            'sections' => $sections,
            'events' => $events,
        ]);
    }

    public function updateSection(SaveReportSectionRequest $request, Report $report, string $sectionKey): RedirectResponse
    {
        $this->ensureOwnership($report);

        if ($report->status === Report::STATUS_SUBMITTED) {
            return redirect()->route('reports.show', ['report' => $report, 'section' => $sectionKey])
                ->with('error', 'Submitted report cannot be edited.');
        }

        $report->sections()->updateOrCreate([
            'section_key' => $sectionKey,
        ], [
            'payload' => $request->validated('data'),
        ]);

        $report->events()->create([
            'user_id' => $request->user()->id,
            'event_type' => 'section_saved',
            'message' => sprintf('Section "%s" saved as draft.', str_replace('_', ' ', $sectionKey)),
        ]);

        return redirect()->route('reports.show', ['report' => $report, 'section' => $sectionKey])
            ->with('success', 'Section saved successfully.');
    }

    public function submit(Request $request, Report $report): RedirectResponse
    {
        $this->ensureOwnership($report);

        if ($report->status === Report::STATUS_SUBMITTED) {
            return redirect()->route('reports.show', ['report' => $report])->with('success', 'Report already submitted.');
        }

        $requiredSections = ReportBlueprint::sectionKeysForAudience($report->audience);
        $savedSections = $report->sections()->pluck('section_key')->all();
        $missingSections = array_values(array_diff($requiredSections, $savedSections));

        if ($missingSections !== []) {
            return redirect()->route('reports.show', ['report' => $report, 'section' => $missingSections[0]])
                ->with('error', 'Complete all sections before submission. Missing: '.implode(', ', array_map(static fn (string $section): string => str_replace('_', ' ', $section), $missingSections)).'.');
        }

        $report->update([
            'status' => Report::STATUS_SUBMITTED,
        ]);

        $report->events()->create([
            'user_id' => $request->user()->id,
            'event_type' => 'submitted',
            'message' => 'Report submitted for review.',
        ]);

        return redirect()->route('reports.show', ['report' => $report])->with('success', 'Report submitted successfully.');
    }

    private function ensureOwnership(Report $report): void
    {
        abort_unless($report->user_id === auth()->id(), 403);
    }
}
