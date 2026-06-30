<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportSection;
use App\Models\User;
use App\Support\ReportBlueprint;
use Illuminate\Database\Seeder;

class ReportDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();
        if (!$user) return;

        // Create sample Developer Report
        $devReport = Report::create([
            'user_id' => $user->id,
            'audience' => Report::AUDIENCE_DEVELOPER,
            'report_type' => Report::TYPE_MONTHLY,
            'report_month' => 1,
            'report_year' => (int) now()->year,
            'status' => Report::STATUS_DRAFT,
        ]);

        foreach (ReportBlueprint::sectionKeysForAudience(Report::AUDIENCE_DEVELOPER) as $sectionKey) {
            $fields = ReportBlueprint::fieldsForSection($sectionKey);
            $payload = [];
            foreach ($fields as $field) {
                $payload[$field['name']] = match ($field['type']) {
                    'number' => rand(1, 100),
                    'select' => !empty($field['options']) ? $field['options'][0] : null,
                    'date' => now()->format('Y-m-d'),
                    default => 'Sample ' . $field['label'],
                };
            }
            ReportSection::create([
                'report_id' => $devReport->id,
                'section_key' => $sectionKey,
                'payload' => $payload,
            ]);
        }

        // Create sample Enterprise Report
        $entReport = Report::create([
            'user_id' => $user->id,
            'audience' => Report::AUDIENCE_ENTERPRISE,
            'report_type' => Report::TYPE_QUARTERLY,
            'report_quarter' => 1,
            'report_year' => (int) now()->year,
            'status' => Report::STATUS_DRAFT,
        ]);

        foreach (ReportBlueprint::sectionKeysForAudience(Report::AUDIENCE_ENTERPRISE) as $sectionKey) {
            $fields = ReportBlueprint::fieldsForSection($sectionKey);
            $payload = [];
            foreach ($fields as $field) {
                $payload[$field['name']] = match ($field['type']) {
                    'number' => rand(1, 100),
                    'select' => !empty($field['options']) ? $field['options'][0] : null,
                    'date' => now()->format('Y-m-d'),
                    default => 'Sample ' . $field['label'],
                };
            }
            ReportSection::create([
                'report_id' => $entReport->id,
                'section_key' => $sectionKey,
                'payload' => $payload,
            ]);
        }
    }
}
