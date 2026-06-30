<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $canViewAllUsers = $user->can('user-list');
        $canViewAllRoles = $user->can('role-list');
        $canViewReports = $user->can('report-list');

        $userCount = $canViewAllUsers ? User::count() : null;
        $roleCount = $canViewAllRoles ? Role::count() : null;

        $reportQuery = Report::query();

        if (! $canViewReports) {
            $reportQuery->where('user_id', $user->id);
        }

        $totalReports = (clone $reportQuery)->count();
        $draftReports = (clone $reportQuery)->where('status', Report::STATUS_DRAFT)->count();
        $submittedReports = (clone $reportQuery)->where('status', Report::STATUS_SUBMITTED)->count();

        $reportTypeCounts = (clone $reportQuery)
            ->selectRaw('report_type, COUNT(*) as total')
            ->groupBy('report_type')
            ->pluck('total', 'report_type');

        $audienceCounts = (clone $reportQuery)
            ->selectRaw('audience, COUNT(*) as total')
            ->groupBy('audience')
            ->pluck('total', 'audience');

        $dateFormat = config('database.default') === 'pgsql'
            ? "TO_CHAR(created_at, 'YYYY-MM')"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $trendRows = (clone $reportQuery)
            ->selectRaw("{$dateFormat} as ym, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $trendLabels = [];
        $trendValues = [];

        foreach (range(5, 0) as $monthsBack) {
            $month = now()->subMonths($monthsBack);
            $key = $month->format('Y-m');
            $trendLabels[] = $month->format('M Y');
            $trendValues[] = (int) ($trendRows[$key]?->total ?? 0);
        }

        $recentReports = (clone $reportQuery)
            ->with('user')
            ->latest('id')
            ->limit(7)
            ->get();

        return view('livewire.dashboard', [
            'userCount' => $userCount,
            'roleCount' => $roleCount,
            'totalReports' => $totalReports,
            'draftReports' => $draftReports,
            'submittedReports' => $submittedReports,
            'reportTypeCounts' => $reportTypeCounts,
            'audienceCounts' => $audienceCounts,
            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,
            'recentReports' => $recentReports,
        ]);
    }
}
