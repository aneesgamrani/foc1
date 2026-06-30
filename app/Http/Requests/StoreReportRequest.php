<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $minYear = 2020;
        $maxYear = (int) now()->year + 1;

        return [
            'audience' => ['required', Rule::in([
                Report::AUDIENCE_DEVELOPER,
                Report::AUDIENCE_ENTERPRISE,
            ])],
            'report_type' => ['required', Rule::in([
                Report::TYPE_MONTHLY,
                Report::TYPE_QUARTERLY,
                Report::TYPE_BIANNUAL,
                Report::TYPE_ANNUAL,
            ])],
            'report_month' => [
                Rule::requiredIf(fn () => $this->input('report_type') === Report::TYPE_MONTHLY),
                'nullable',
                'integer',
                'between:1,12',
            ],
            'report_quarter' => [
                Rule::requiredIf(fn () => $this->input('report_type') === Report::TYPE_QUARTERLY),
                'nullable',
                'integer',
                'between:1,4',
            ],
            'biannual_half' => [
                Rule::requiredIf(fn () => $this->input('report_type') === Report::TYPE_BIANNUAL),
                'nullable',
                'integer',
                'between:1,2',
            ],
            'report_year' => ['required', 'integer', 'between:'.$minYear.','.$maxYear],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'report_month.required' => 'Please select month for monthly report.',
            'report_quarter.required' => 'Please select quarter for quarterly report.',
            'biannual_half.required' => 'Please select first or second half for biannual report.',
        ];
    }
}
