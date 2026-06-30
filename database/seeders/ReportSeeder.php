<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        if (! $user) {
            return;
        }

        $seedItems = [
            [
                'audience' => Report::AUDIENCE_DEVELOPER,
                'report_type' => Report::TYPE_MONTHLY,
                'report_month' => 1,
                'report_quarter' => null,
                'biannual_half' => null,
                'report_year' => (int) now()->year,
                'status' => Report::STATUS_DRAFT,
            ],
            [
                'audience' => Report::AUDIENCE_DEVELOPER,
                'report_type' => Report::TYPE_QUARTERLY,
                'report_month' => null,
                'report_quarter' => 1,
                'biannual_half' => null,
                'report_year' => (int) now()->year,
                'status' => Report::STATUS_DRAFT,
            ],
            [
                'audience' => Report::AUDIENCE_ENTERPRISE,
                'report_type' => Report::TYPE_BIANNUAL,
                'report_month' => null,
                'report_quarter' => null,
                'biannual_half' => 1,
                'report_year' => (int) now()->year,
                'status' => Report::STATUS_DRAFT,
            ],
            [
                'audience' => Report::AUDIENCE_ENTERPRISE,
                'report_type' => Report::TYPE_ANNUAL,
                'report_month' => null,
                'report_quarter' => null,
                'biannual_half' => null,
                'report_year' => (int) now()->year,
                'status' => Report::STATUS_DRAFT,
            ],
        ];

        foreach ($seedItems as $seedItem) {
            Report::query()->updateOrCreate([
                'user_id' => $user->id,
                'audience' => $seedItem['audience'],
                'report_type' => $seedItem['report_type'],
                'report_year' => $seedItem['report_year'],
                'report_month' => $seedItem['report_month'],
                'report_quarter' => $seedItem['report_quarter'],
                'biannual_half' => $seedItem['biannual_half'],
            ], $seedItem + [
                'user_id' => $user->id,
            ]);
        }
    }
}
