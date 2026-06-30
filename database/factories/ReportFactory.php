<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([
            Report::TYPE_MONTHLY,
            Report::TYPE_QUARTERLY,
            Report::TYPE_BIANNUAL,
            Report::TYPE_ANNUAL,
        ]);

        return [
            'user_id' => User::factory(),
            'audience' => fake()->randomElement([Report::AUDIENCE_DEVELOPER, Report::AUDIENCE_ENTERPRISE]),
            'report_type' => $type,
            'report_month' => $type === Report::TYPE_MONTHLY ? fake()->numberBetween(1, 12) : null,
            'report_quarter' => $type === Report::TYPE_QUARTERLY ? fake()->numberBetween(1, 4) : null,
            'biannual_half' => $type === Report::TYPE_BIANNUAL ? fake()->numberBetween(1, 2) : null,
            'report_year' => fake()->numberBetween(2020, (int) now()->year + 1),
            'status' => Report::STATUS_DRAFT,
        ];
    }
}
