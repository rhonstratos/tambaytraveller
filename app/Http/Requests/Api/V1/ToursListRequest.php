<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToursListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price_from' => ['numeric'],
            'price_to' => ['numeric'],
            'date_from' => ['date'],
            'date_to' => ['date'],
            'sort_order' => ['string', Rule::in(['asc', 'desc'])],
            'sort_by' => ['string', Rule::in(['price'])],
        ];
    }

    public function messages(): array
    {
        return [
            'sort_order' => "The 'sort_order' parameter only accepts 'asc' and 'desc' value",
            'sort_by' => "The 'sort_by' parameter only accepts 'price' value",
        ];
    }
}
