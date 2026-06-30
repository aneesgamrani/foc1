<?php

namespace App\Http\Requests;

use App\Models\Report;
use App\Support\ReportBlueprint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveReportSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $report = $this->route('report');

        return $report instanceof Report
            && $this->user() !== null
            && $report->user_id === $this->user()->id;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $sectionKey = (string) $this->route('sectionKey');
        $allowedSections = ReportBlueprint::sectionKeysForAudience((string) $this->route('report')->audience);

        $rules = [
            'section_key' => ['required', Rule::in($allowedSections)],
            'data' => ['required', 'array'],
        ];

        foreach (ReportBlueprint::fieldsForSection($sectionKey) as $field) {
            $fieldRules = [];

            if (! empty($field['required'])) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            $fieldRules[] = match ($field['type']) {
                'number' => 'numeric',
                'date' => 'date',
                'select' => Rule::in($field['options'] ?? []),
                default => 'string',
            };

            $rules['data.'.$field['name']] = $fieldRules;
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'section_key' => (string) $this->route('sectionKey'),
        ]);
    }
}
