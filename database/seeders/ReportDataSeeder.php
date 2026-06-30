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
        $developer = User::where('developer_type', 1)->first();
        $enterprise1 = User::where('developer_type', 2)->orderBy('id')->first();
        $enterprise2 = User::where('developer_type', 2)->orderBy('id')->skip(1)->first();

        if (!$developer || !$enterprise1) {
            $this->command->warn('UserSeeder must run before ReportDataSeeder.');
            return;
        }

        // Enterprise 1 — submitted so developer auto-calc can aggregate it
        $ent1Report = Report::updateOrCreate([
            'user_id' => $enterprise1->id,
            'audience' => Report::AUDIENCE_ENTERPRISE,
            'report_type' => Report::TYPE_ANNUAL,
            'report_month' => null,
            'report_quarter' => null,
            'biannual_half' => null,
            'report_year' => (int) now()->year,
        ], ['status' => Report::STATUS_SUBMITTED]);

        $this->seedEnterpriseSections($ent1Report, [
            'enterprise_name' => $enterprise1->company_name,
            'country_of_origin' => 'Pakistan',
            'investment_till_date_mn' => 450.50,
            'custom_duty_amount_mn' => 12.75,
            'income_tax_amount_mn' => 8.40,
            'fiscal_years' => [
                ['fiscal_year' => '2022-23', 'production_status' => 'Non-Production', 'turnover_selected_fy_mn' => 0, 'turnover_expected_fy_mn' => 100, 'expected_exports_mn' => 0, 'generated_exports_mn' => 0, 'imports_mn' => 50, 'production_mn' => 0, 'non_production_reason' => 'Construction phase ongoing'],
                ['fiscal_year' => '2023-24', 'production_status' => 'Production Started', 'turnover_selected_fy_mn' => 185, 'turnover_expected_fy_mn' => 200, 'expected_exports_mn' => 80, 'generated_exports_mn' => 65, 'imports_mn' => 30, 'production_mn' => 185, 'non_production_reason' => ''],
            ],
        ]);

        // Enterprise 2 — submitted
        if ($enterprise2) {
            $ent2Report = Report::updateOrCreate([
                'user_id' => $enterprise2->id,
                'audience' => Report::AUDIENCE_ENTERPRISE,
                'report_type' => Report::TYPE_ANNUAL,
                'report_month' => null,
                'report_quarter' => null,
                'biannual_half' => null,
                'report_year' => (int) now()->year,
            ], ['status' => Report::STATUS_SUBMITTED]);

            $this->seedEnterpriseSections($ent2Report, [
                'enterprise_name' => $enterprise2->company_name,
                'country_of_origin' => 'China',
                'investment_till_date_mn' => 1200.00,
                'custom_duty_amount_mn' => 34.20,
                'income_tax_amount_mn' => 21.50,
                'fiscal_years' => [
                    ['fiscal_year' => '2023-24', 'production_status' => 'Production Started', 'turnover_selected_fy_mn' => 540, 'turnover_expected_fy_mn' => 600, 'expected_exports_mn' => 300, 'generated_exports_mn' => 280, 'imports_mn' => 120, 'production_mn' => 540, 'non_production_reason' => ''],
                ],
            ]);
        }

        // Zone Developer report — draft, auto-calc fields will populate from enterprises above
        $devReport = Report::updateOrCreate([
            'user_id' => $developer->id,
            'audience' => Report::AUDIENCE_DEVELOPER,
            'report_type' => Report::TYPE_ANNUAL,
            'report_month' => null,
            'report_quarter' => null,
            'biannual_half' => null,
            'report_year' => (int) now()->year,
        ], ['status' => Report::STATUS_DRAFT]);

        $this->seedDeveloperSections($devReport);
    }

    protected function seedEnterpriseSections(Report $report, array $hints): void
    {
        $sectionPayloads = [
            'enterprise_details' => [
                'sid' => 'SID-' . str_pad($report->id, 4, '0', STR_PAD_LEFT),
                'ntn_no' => '1234567-8',
                'enterprise_name' => $hints['enterprise_name'],
                'entity_type' => 'Company',
                'country_of_origin' => $hints['country_of_origin'],
                'principal_activity' => 'Manufacturing and export of industrial goods',
                'secp_registration_no' => 'SECP-' . rand(10000, 99999),
                'address' => 'Plot #A-12, SEZ Industrial Area',
                'principal_officer' => 'Muhammad Ali Khan',
                'principal_officer_contact' => '0300-1234567',
            ],
            'enterprise_plot_info' => [
                'plot_no' => 'A-' . rand(1, 50),
                'plot_size_acres' => 2.5,
                'plot_price_per_acre_mn' => 15.00,
                'plot_phase' => 'Phase-I',
            ],
            'enterprise_construction' => [
                'land_allotment_letter_date' => '2021-06-15',
                'zone_enterprise_notification_date' => '2021-09-01',
                'allottee_type' => 'SEZ Allottee',
                'possession_status' => 'Under possession',
                'construction_status' => 'Completed',
                'construction_start_date' => '2021-10-01',
                'completion_percentage' => 100,
            ],
            'enterprise_investment' => [
                'estimated_project_cost_mn' => round($hints['investment_till_date_mn'] * 1.2, 2),
                'investment_till_date_mn' => $hints['investment_till_date_mn'],
            ],
            'enterprise_tax_exemptions' => [
                'custom_duty_availed' => 'Yes',
                'custom_duty_date' => '2022-01-01',
                'custom_duty_amount_mn' => $hints['custom_duty_amount_mn'],
                'income_tax_availed' => 'Yes',
                'income_tax_date' => '2022-01-01',
                'income_tax_amount_mn' => $hints['income_tax_amount_mn'],
            ],
            'enterprise_production' => [
                'fiscal_years' => $hints['fiscal_years'],
                'production_commencement_date' => '2023-07-01',
                'developer_verification_date' => null,
            ],
            'enterprise_employment' => [
                'estimated_employment_generation' => 200,
                'current_employment_total' => 145,
                'total_employees_working' => 145,
                'local_employees' => 130,
                'foreign_employees' => 15,
                'current_direct_employment' => 120,
                'current_indirect_employment' => 25,
            ],
            'enterprise_utility' => [
                'gas_requirement_mmcfd' => 0.5,
                'electricity_requirement_kw' => 500,
                'water_gpd' => 10000,
                'telephone_connections' => 10,
                'waste_water_treatment' => 'Yes',
            ],
        ];

        foreach ($sectionPayloads as $sectionKey => $payload) {
            ReportSection::updateOrCreate(
                ['report_id' => $report->id, 'section_key' => $sectionKey],
                ['payload' => $payload]
            );
        }
    }

    protected function seedDeveloperSections(Report $report): void
    {
        $sectionPayloads = [
            'developer_details' => [
                'sez_name' => 'Model Industrial Zone SEZ',
                'province' => 'Punjab',
                'public_private' => 'Private',
                'developer_name' => 'SEZ Development Authority',
                'notification_date' => '2019-03-15',
                'development_agreement_date' => '2019-06-01',
                'status_working' => 'Working',
                'address' => 'Main GT Road, Lahore',
                'principal_officer' => 'Dr. Zafar Iqbal',
                'contact_person' => 'Sadia Malik',
                'contact_person_number' => '042-35750000',
                'developer_ntn' => '0123456-7',
                'exchange_rate' => 278.5,
            ],
            'developer_plot_details' => [
                'total_area_acres' => 500,
                'industrial_area_acres' => 350,
                'saleable_commercial_area_acres' => 80,
                'total_saleable_area_acres' => 430,
                'total_area_sold_acres' => 120,
                'total_number_of_plots' => 86,
                'total_number_of_plots_sold' => 24,
                'revenue_industrial_plots_mn' => 1800,
                'revenue_commercial_plots_mn' => 240,
                'price_per_acre_approval_mn' => 12,
                'current_price_per_acre_vogue_mn' => 18,
                'key_products_line_businesses' => 'Textiles, Pharmaceuticals, Light Engineering, Food Processing',
                'remarks' => 'Phase-I fully developed. Phase-II under development.',
            ],
            'developer_development_status' => [
                'land_acquisition_status' => '100% acquired',
                'land_acquisition_remarks' => 'All land acquired and possession taken.',
                'roads_status' => '85% complete',
                'roads_remarks' => 'Main arteries complete; secondary roads in progress.',
                'electricity_status' => '132 KV grid station operational',
                'electricity_remarks' => '',
                'gas_status' => 'Available',
                'gas_remarks' => 'SSGC connection established.',
                'telephone_status' => 'Available',
                'telephone_remarks' => '',
                'dsl_fiber_optic_status' => 'Fiber laid in Phase-I',
                'dsl_fiber_optic_remarks' => '',
                'water_status' => 'Available',
                'water_remarks' => 'Underground reservoir operational.',
                'sewerage_status' => 'Available',
                'sewerage_remarks' => '',
                'waste_water_treatment_status' => 'Under construction',
                'waste_water_treatment_remarks' => 'ETP expected by Q3 2025.',
                'security_status' => 'Operational',
                'security_remarks' => '24/7 security with CCTV.',
                'fire_fighting_status' => 'Available',
                'fire_fighting_remarks' => '',
                'medical_facility_status' => 'First-aid center operational',
                'medical_facility_remarks' => '',
                'education_vocational_status' => 'Planned',
                'education_vocational_remarks' => 'Vocational center planned for Phase-II.',
                'any_other_facility_status' => 'Mosque, Canteen',
                'any_other_facility_remarks' => '',
            ],
            'developer_investment' => [
                'project_cost_mn' => 8500,
                'development_cost_mn' => 3200,
                'local_units' => 1,       // will be recalculated via auto-calc
                'foreign_units' => 1,
                'local_investment_bn' => 0.4505,
                'foreign_investment_bn' => 1.2,
                'estimated_local_investment_bn' => 2.5,
                'estimated_foreign_investment_bn' => 1.8,
                'total_estimated_investment_bn' => 4.3,
                'companies_applied' => 8,
                'companies_pending' => 3,
                'pendency_reason' => 'Documentation incomplete for 3 applicants.',
            ],
            'developer_tax_exemptions' => [
                'dev_custom_duty_availed' => 'Yes',
                'dev_custom_duty_date' => '2020-07-01',
                'dev_custom_duty_amount_mn' => 95.30,
                'dev_income_tax_availed' => 'Yes',
                'dev_income_tax_date' => '2020-07-01',
                'dev_income_tax_amount_mn' => 42.80,
                'ent_custom_duty_availed' => 'Yes',
                'ent_custom_duty_date' => '2022-01-01',
                'ent_custom_duty_amount_mn' => 46.95,   // 12.75 + 34.20
                'ent_income_tax_availed' => 'Yes',
                'ent_income_tax_date' => '2022-01-01',
                'ent_income_tax_amount_mn' => 29.90,    // 8.40 + 21.50
            ],
            'developer_production' => [
                'fiscal_years' => [
                    ['fiscal_year' => '2021-22', 'production_status' => 'Non-Production', 'turnover_selected_fy_mn' => 0, 'turnover_expected_fy_mn' => 500, 'expected_exports_mn' => 0, 'generated_exports_mn' => 0, 'imports_mn' => 80, 'production_mn' => 0, 'non_production_reason' => 'Zone enterprises still in construction'],
                    ['fiscal_year' => '2022-23', 'production_status' => 'Non-Production', 'turnover_selected_fy_mn' => 0, 'turnover_expected_fy_mn' => 800, 'expected_exports_mn' => 200, 'generated_exports_mn' => 0, 'imports_mn' => 130, 'production_mn' => 0, 'non_production_reason' => 'First enterprise still commissioning'],
                    ['fiscal_year' => '2023-24', 'production_status' => 'Production Started', 'turnover_selected_fy_mn' => 725, 'turnover_expected_fy_mn' => 800, 'expected_exports_mn' => 380, 'generated_exports_mn' => 345, 'imports_mn' => 150, 'production_mn' => 725, 'non_production_reason' => ''],
                ],
                'total_companies_production' => 2,
                'total_companies_construction' => 0,
            ],
            'developer_employment' => [
                'estimated_employment_generation' => 5000,
                'current_employment_total' => 290,
                'total_employees_working' => 290,
                'local_employees' => 260,
                'foreign_employees' => 30,
            ],
            'developer_utility' => [
                'gas_requirement_mmcfd' => 2.5,
                'gas_rate' => 1850,
                'electricity_requirement_kw' => 15000,
                'electricity_rate' => 28.5,
                'water_gpd' => 250000,
                'water_rate' => 0.05,
                'telephone_connections' => 50,
                'telephone_charges' => 12000,
                'waste_water_treatment' => 'Yes',
                'waste_treatment_charges' => 5000,
                'development_charges_yearly_mn' => 18,
                'maintenance_charges_yearly_mn' => 12,
            ],
        ];

        foreach ($sectionPayloads as $sectionKey => $payload) {
            ReportSection::updateOrCreate(
                ['report_id' => $report->id, 'section_key' => $sectionKey],
                ['payload' => $payload]
            );
        }
    }
}
