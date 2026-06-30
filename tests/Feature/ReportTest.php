<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_reports_page_requires_permission(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertForbidden();
    }

    public function test_user_can_create_monthly_report_entry(): void
    {
        $user = User::factory()->create();
        Permission::findOrCreate('report-list', 'web');
        Permission::findOrCreate('report-create', 'web');
        Permission::findOrCreate('report-edit', 'web');
        $user->givePermissionTo(['report-list', 'report-create']);

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'audience' => Report::AUDIENCE_DEVELOPER,
            'report_type' => Report::TYPE_MONTHLY,
            'report_month' => 2,
            'report_year' => now()->year,
        ]);

        $report = Report::query()->where('user_id', $user->id)->latest('id')->first();

        $user->givePermissionTo('report-edit');

        $response->assertRedirect(route('reports.show', ['report' => $report, 'section' => 'developer_details']));

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'audience' => Report::AUDIENCE_DEVELOPER,
            'report_type' => Report::TYPE_MONTHLY,
            'report_month' => 2,
            'report_year' => now()->year,
        ]);
    }

    public function test_quarterly_report_requires_quarter_value(): void
    {
        $user = User::factory()->create();
        Permission::findOrCreate('report-create', 'web');
        $user->givePermissionTo('report-create');

        $response = $this->actingAs($user)->from(route('reports.index'))->post(route('reports.store'), [
            'audience' => Report::AUDIENCE_DEVELOPER,
            'report_type' => Report::TYPE_QUARTERLY,
            'report_year' => now()->year,
        ]);

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHasErrors(['report_quarter']);
    }

    public function test_user_can_save_report_section(): void
    {
        $user = User::factory()->create();
        Permission::findOrCreate('report-edit', 'web');
        $user->givePermissionTo('report-edit');

        $report = Report::factory()->create([
            'user_id' => $user->id,
            'audience' => Report::AUDIENCE_DEVELOPER,
            'status' => Report::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($user)->put(route('reports.sections.update', ['report' => $report, 'sectionKey' => 'developer_details']), [
            'data' => [
                'sez_name' => 'OVSEZ',
                'province' => 'Punjab',
                'public_private' => 'Private',
                'developer_name' => 'FOC',
                'status_working' => 'Working',
                'address' => 'Rawalpindi',
                'exchange_rate' => 278.50,
                'project_cost_bn' => 10.5,
                'development_cost_bn' => 5.2,
            ],
        ]);

        $response->assertRedirect(route('reports.show', ['report' => $report, 'section' => 'developer_details']));

        $this->assertDatabaseHas('report_sections', [
            'report_id' => $report->id,
            'section_key' => 'developer_details',
        ]);
    }

    public function test_report_submit_requires_all_sections(): void
    {
        $user = User::factory()->create();
        Permission::findOrCreate('report-submit', 'web');
        $user->givePermissionTo('report-submit');

        $report = Report::factory()->create([
            'user_id' => $user->id,
            'audience' => Report::AUDIENCE_DEVELOPER,
            'status' => Report::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($user)->post(route('reports.submit', $report));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertSame(Report::STATUS_DRAFT, $report->fresh()->status);
    }
}
