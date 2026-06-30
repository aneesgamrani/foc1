<?php

namespace App\Support;

class ReportBlueprint
{
    public static function sectionDefinitions(): array
    {
        return [
            'developer_details' => [
                'label' => 'Details',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'sez_name', 'label' => 'Name of SEZ', 'type' => 'text', 'required' => true],
                    ['name' => 'province', 'label' => 'Province', 'type' => 'text', 'required' => true],
                    ['name' => 'public_private', 'label' => 'Public / Private', 'type' => 'select', 'options' => ['Public', 'Private'], 'required' => true],
                    ['name' => 'developer_name', 'label' => 'Developer Name :', 'type' => 'text', 'required' => true],
                    ['name' => 'notification_date', 'label' => 'Date of Notification of SEZ', 'type' => 'date'],
                    ['name' => 'development_agreement_date', 'label' => 'Date of Signing of Development Agreement (DA)', 'type' => 'date'],
                    ['name' => 'status_working', 'label' => 'Status (Working / Not Working) :', 'type' => 'select', 'options' => ['Working', 'Not-Working'], 'required' => true],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'required' => true],
                    ['name' => 'principal_officer', 'label' => 'Name of Principal Officer :', 'type' => 'text'],
                    ['name' => 'contact_person', 'label' => 'Name of Contact Person :', 'type' => 'text'],
                    ['name' => 'contact_person_number', 'label' => 'Contact Number of Contact Person :', 'type' => 'tel'],
                    ['name' => 'developer_ntn', 'label' => 'Developer NTN :', 'type' => 'text'],
                    ['name' => 'exchange_rate', 'label' => 'Exchange Rate Used :', 'type' => 'number', 'step' => '0.0001', 'required' => true],
                ],
            ],
            'developer_plot_details' => [
                'label' => 'SEZ Land Info',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'total_area_acres', 'label' => 'Total Area (Acres) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'industrial_area_acres', 'label' => 'Industrial Area (Acres) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'saleable_commercial_area_acres', 'label' => 'Saleable Commercial Area (Acres) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'total_saleable_area_acres', 'label' => 'Total Saleable Area (Acres) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'total_area_sold_acres', 'label' => 'Total Area Sold (Acres) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'total_number_of_plots', 'label' => 'Total Number of Plots :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'total_number_of_plots_sold', 'label' => 'Total Number of Plots Sold :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'revenue_industrial_plots_mn', 'label' => 'Revenue from Industrial Plots (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'revenue_commercial_plots_mn', 'label' => 'Revenue from Commercial Plots (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'price_per_acre_approval_mn', 'label' => 'Price Per Acre at the Time of Approval (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'current_price_per_acre_vogue_mn', 'label' => 'Current Price Per Acre in Vogue (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'key_products_line_businesses', 'label' => 'Key Products / Line of Businesses :', 'type' => 'textarea'],
                    ['name' => 'remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                ],
            ],
            'developer_development_status' => [
                'label' => 'Development Status',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'land_acquisition_status', 'label' => 'Land Acquisition :', 'type' => 'text'],
                    ['name' => 'land_acquisition_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'roads_status', 'label' => 'Roads:', 'type' => 'text'],
                    ['name' => 'roads_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'electricity_status', 'label' => 'Electricity:', 'type' => 'text'],
                    ['name' => 'electricity_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'gas_status', 'label' => 'Gas:', 'type' => 'text'],
                    ['name' => 'gas_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'telephone_status', 'label' => 'Telephone :', 'type' => 'text'],
                    ['name' => 'telephone_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'dsl_fiber_optic_status', 'label' => 'DSL / Fiber Optic :', 'type' => 'text'],
                    ['name' => 'dsl_fiber_optic_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'water_status', 'label' => 'Water:', 'type' => 'text'],
                    ['name' => 'water_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'sewerage_status', 'label' => 'Sewerage:', 'type' => 'text'],
                    ['name' => 'sewerage_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'waste_water_treatment_status', 'label' => 'Waste Water Treatment:', 'type' => 'text'],
                    ['name' => 'waste_water_treatment_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'security_status', 'label' => 'Security:', 'type' => 'text'],
                    ['name' => 'security_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'fire_fighting_status', 'label' => 'Fire Fighting :', 'type' => 'text'],
                    ['name' => 'fire_fighting_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'medical_facility_status', 'label' => 'Medical Facility :', 'type' => 'text'],
                    ['name' => 'medical_facility_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'education_vocational_status', 'label' => 'Education and Vocational Training :', 'type' => 'text'],
                    ['name' => 'education_vocational_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                    ['name' => 'any_other_facility_status', 'label' => 'Any Other Facility:', 'type' => 'text'],
                    ['name' => 'any_other_facility_remarks', 'label' => 'Remarks:', 'type' => 'textarea'],
                ],
            ],
            'developer_investment' => [
                'label' => 'Investment Details',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'project_cost_mn', 'label' => 'Project Cost (Rs in Millions)', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'development_cost_mn', 'label' => 'Development Cost (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'local_units', 'label' => 'Local Units:', 'type' => 'number', 'step' => '1'],
                    ['name' => 'foreign_units', 'label' => 'Foreign Units :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'local_investment_bn', 'label' => 'Local Investment (Rs in Billion) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'foreign_investment_bn', 'label' => 'Foreign Investment (Rs in Billion) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'estimated_local_investment_bn', 'label' => 'Estimated Local Investment (Rs in Billion) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'estimated_foreign_investment_bn', 'label' => 'Estimated Foreign Investment (Rs in Billion) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'total_estimated_investment_bn', 'label' => 'Total Estimated Investment (Rs in Billion) (Local + Foreign) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'companies_applied', 'label' => 'Companies Applied for Zone Enterprise Status :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'companies_pending', 'label' => 'Companies Pending for Zone Enterprise Status :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'pendency_reason', 'label' => 'Reason for Pendency:', 'type' => 'textarea'],
                ],
            ],
            'developer_tax_exemptions' => [
                'label' => 'Tax Exemptions',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'custom_duty_availed', 'label' => 'Custom Duty Exemption Availed :', 'type' => 'select', 'options' => ['Yes', 'No']],
                    ['name' => 'custom_duty_date', 'label' => 'Custom Duty Exemption Date :', 'type' => 'date'],
                    ['name' => 'custom_duty_amount_mn', 'label' => 'Amount of Custom Duty Exemption Availed (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'income_tax_availed', 'label' => 'Income Tax Exemption Availed :', 'type' => 'select', 'options' => ['Yes', 'No']],
                    ['name' => 'income_tax_date', 'label' => 'Income Tax Exemption Date :', 'type' => 'date'],
                    ['name' => 'income_tax_amount_mn', 'label' => 'Amount of Income Tax Exemption Availed (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                ],
            ],
            'developer_production' => [
                'label' => 'Production Details',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'fiscal_year', 'label' => 'Fiscal Year', 'type' => 'text'],
                    ['name' => 'turnover_selected_fy_mn', 'label' => 'Turnover for the Selected Fiscal Year (Rs In Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'turnover_expected_fy_mn', 'label' => 'Turnover Expected for the Selected Fiscal Year (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'expected_exports_mn', 'label' => 'Expected Exports (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'generated_exports_mn', 'label' => 'Generated Exports (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'imports_mn', 'label' => 'Imports (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'production_mn', 'label' => 'Production (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'production_status', 'label' => 'Production Status :', 'type' => 'select', 'options' => ['Production Started', 'Non-Production']],
                    ['name' => 'non_production_reason', 'label' => 'Reasons for Non-Production :', 'type' => 'textarea'],
                    ['name' => 'total_companies_production', 'label' => 'Total Companies in Production :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'total_companies_construction', 'label' => 'Total Companies in Construction :', 'type' => 'number', 'step' => '1'],
                ],
            ],
            'developer_employment' => [
                'label' => 'Employment Details',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'estimated_employment_generation', 'label' => 'Estimated Employment Generation :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'current_employment_total', 'label' => 'Current Employment (Direct + Indirect): ', 'type' => 'number', 'step' => '1'],
                    ['name' => 'total_employees_working', 'label' => 'Total No of Employees Currently Working in SEZ :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'local_employees', 'label' => 'Local Employees :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'foreign_employees', 'label' => 'Foreign Employees :', 'type' => 'number', 'step' => '1'],
                ],
            ],
            'developer_utility' => [
                'label' => 'Utility Requirements',
                'audiences' => ['developer'],
                'fields' => [
                    ['name' => 'gas_requirement_mmcfd', 'label' => 'Total Gas Requirement (MMCFD) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'gas_rate', 'label' => 'Gas Rate (Rs / MMBTU) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'electricity_requirement_kw', 'label' => 'Total Electricity Requirement (KW)', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'electricity_rate', 'label' => 'Electricity Rate (Rs / KWh) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'water_gpd', 'label' => 'Water (GPD) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'water_rate', 'label' => 'Water Rate (Rs / Gallon) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'telephone_connections', 'label' => 'Telephone (Land Line Connections) :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'telephone_charges', 'label' => 'Telephone Charges :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'waste_water_treatment', 'label' => 'Waste Water Treatment:', 'type' => 'select', 'options' => ['Yes', 'No']],
                    ['name' => 'waste_treatment_charges', 'label' => 'Waste Water Treatment Charges :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'development_charges_yearly_mn', 'label' => 'Development Charges (Rs in Million / Year) (if any):', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'maintenance_charges_yearly_mn', 'label' => 'Maintenance Charges (Rs in Million / Year) (if any):', 'type' => 'number', 'step' => '0.01'],
                ],
            ],
            'enterprise_details' => [
                'label' => 'Details',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'sid', 'label' => 'SID:', 'type' => 'text'],
                    ['name' => 'ntn_no', 'label' => 'NTN No:', 'type' => 'text'],
                    ['name' => 'enterprise_name', 'label' => 'Enterprise Name:', 'type' => 'text', 'required' => true],
                    ['name' => 'entity_type', 'label' => 'AOP / Individual / Company :', 'type' => 'text'],
                    ['name' => 'country_of_origin', 'label' => 'Country of Origin:', 'type' => 'text'],
                    ['name' => 'principal_activity', 'label' => 'Principle Activity / Item of Production :', 'type' => 'textarea'],
                    ['name' => 'secp_registration_no', 'label' => 'SECP Registration No :', 'type' => 'text'],
                    ['name' => 'address', 'label' => 'Address:', 'type' => 'text'],
                    ['name' => 'principal_officer', 'label' => 'Name of Principal Officer :', 'type' => 'text'],
                    ['name' => 'principal_officer_contact', 'label' => 'Contact No of Principal Officer :', 'type' => 'text'],
                ],
            ],
            'enterprise_plot_info' => [
                'label' => 'Plot Info',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'plot_no', 'label' => 'Plot No :', 'type' => 'text'],
                    ['name' => 'plot_size_acres', 'label' => 'Plot Size (Acres) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'plot_price_per_acre_mn', 'label' => 'Per Acre Price of Plot (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'plot_phase', 'label' => 'Plot Phase:', 'type' => 'text'],
                ],
            ],
            'enterprise_construction' => [
                'label' => 'Construction Status',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'land_allotment_letter_date', 'label' => 'Date of Land Allotment Letter :', 'type' => 'date'],
                    ['name' => 'zone_enterprise_notification_date', 'label' => 'Date of Notification as Zone Enterprise :', 'type' => 'date'],
                    ['name' => 'allottee_type', 'label' => 'Industrial Estate (IE) Allottee or SEZ Allottee:', 'type' => 'select', 'options' => ['IE Allottee', 'SEZ Allottee']],
                    ['name' => 'possession_status', 'label' => 'Status of Possession :', 'type' => 'select', 'options' => ['Under possession', 'Vacant']],
                    ['name' => 'construction_status', 'label' => 'Construction Status :', 'type' => 'select', 'options' => ['Completed', 'Under construction', 'Others']],
                    ['name' => 'construction_start_date', 'label' => 'Date of Commencement of Construction :', 'type' => 'date'],
                    ['name' => 'completion_percentage', 'label' => 'Percentage Completion till date :', 'type' => 'number', 'step' => '0.01'],
                ],
            ],
            'enterprise_investment' => [
                'label' => 'Investment Details',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'estimated_project_cost_mn', 'label' => 'Estimated Project Cost (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'investment_till_date_mn', 'label' => 'Investment Till Date (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                ],
            ],
            'enterprise_tax_exemptions' => [
                'label' => 'Tax Exemptions',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'custom_duty_availed', 'label' => 'Custom Duty Exemption Availed :', 'type' => 'select', 'options' => ['Yes', 'No']],
                    ['name' => 'custom_duty_date', 'label' => 'Custom Duty Exemption Date :', 'type' => 'date'],
                    ['name' => 'custom_duty_amount_mn', 'label' => 'Amount of Custom Duty Exemption Availed (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'income_tax_availed', 'label' => 'Income Tax Exemption Availed :', 'type' => 'select', 'options' => ['Yes', 'No']],
                    ['name' => 'income_tax_date', 'label' => 'Income Tax Exemption Date :', 'type' => 'date'],
                    ['name' => 'income_tax_amount_mn', 'label' => 'Amount of Income Tax Exemption Availed (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                ],
            ],
            'enterprise_production' => [
                'label' => 'Production Details',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'fiscal_year', 'label' => 'Fiscal Year :', 'type' => 'text'],
                    ['name' => 'turnover_selected_fy_mn', 'label' => 'Turnover for the Selected Fiscal Year (Rs In Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'turnover_expected_fy_mn', 'label' => 'Turnover Expected for the Selected Fiscal Year (Rs in Million) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'expected_exports_mn', 'label' => 'Expected Exports (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'generated_exports_mn', 'label' => 'Generated Exports (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'imports_mn', 'label' => 'Imports (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'production_mn', 'label' => 'Production (Rs in Millions) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'production_status', 'label' => 'Production Status :', 'type' => 'select', 'options' => ['Production Started', 'Non-Production']],
                    ['name' => 'production_commencement_date', 'label' => 'Date of Commencement of Production :', 'type' => 'date'],
                    ['name' => 'non_production_reason', 'label' => 'Reasons for Non-Production :', 'type' => 'textarea'],
                    ['name' => 'developer_verification_date', 'label' => 'Current Status Verification Date by Developer:', 'type' => 'date'],
                ],
            ],
            'enterprise_employment' => [
                'label' => 'Employment Details',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'estimated_employment_generation', 'label' => 'Estimated Employment Generation :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'current_employment_total', 'label' => 'Current Employment (Direct + Indirect): ', 'type' => 'number', 'step' => '1'],
                    ['name' => 'total_employees_working', 'label' => 'Total No of Employees Currently Working in SEZ :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'local_employees', 'label' => 'Local Employees :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'foreign_employees', 'label' => 'Foreign Employees :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'current_direct_employment', 'label' => 'Current Direct Employment (Local + Foreigner): ', 'type' => 'number', 'step' => '1'],
                    ['name' => 'current_indirect_employment', 'label' => 'Current In-Direct Employment:', 'type' => 'number', 'step' => '1'],
                ],
            ],
            'enterprise_utility' => [
                'label' => 'Utility Requirements',
                'audiences' => ['enterprise'],
                'fields' => [
                    ['name' => 'gas_requirement_mmcfd', 'label' => 'Gas Requirement (MMCFD) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'electricity_requirement_kw', 'label' => 'Electricity Requirement (KW)', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'water_gpd', 'label' => 'Water (GPD) :', 'type' => 'number', 'step' => '0.01'],
                    ['name' => 'telephone_connections', 'label' => 'Telephone (Land Line Connections) :', 'type' => 'number', 'step' => '1'],
                    ['name' => 'waste_water_treatment', 'label' => 'Waste Water Treatment:', 'type' => 'select', 'options' => ['Yes', 'No']],
                ],
            ],
        ];
    }

    public static function sectionKeysForAudience(string $audience): array
    {
        $definitions = self::sectionDefinitions();
        $keys = [];

        foreach ($definitions as $key => $definition) {
            if (in_array($audience, $definition['audiences'], true)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    public static function fieldsForSection(string $sectionKey): array
    {
        $definitions = self::sectionDefinitions();

        return $definitions[$sectionKey]['fields'] ?? [];
    }
}