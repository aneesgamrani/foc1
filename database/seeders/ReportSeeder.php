<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $developer = User::where('developer_type', 1)->first();
        $enterprise = User::where('developer_type', 2)->first();

        if (!$developer || !$enterprise) {
            $this->command->warn('UserSeeder must run before ReportSeeder — no typed users found.');
            return;
        }

        // Developer reports
        foreach ([
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
        ] as $item) {
            Report::updateOrCreate(
                ['user_id' => $developer->id, 'audience' => $item['audience'], 'report_type' => $item['report_type'], 'report_year' => $item['report_year'], 'report_month' => $item['report_month'], 'report_quarter' => $item['report_quarter'], 'biannual_half' => $item['biannual_half']],
                $item + ['user_id' => $developer->id]
            );
        }

        // Enterprise reports
        foreach ([
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
        ] as $item) {
            Report::updateOrCreate(
                ['user_id' => $enterprise->id, 'audience' => $item['audience'], 'report_type' => $item['report_type'], 'report_year' => $item['report_year'], 'report_month' => $item['report_month'], 'report_quarter' => $item['report_quarter'], 'biannual_half' => $item['biannual_half']],
                $item + ['user_id' => $enterprise->id]
            );
        }
    }
}
