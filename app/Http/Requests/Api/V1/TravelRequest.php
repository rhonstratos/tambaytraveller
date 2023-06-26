<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TravelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_public' => ['boolean'],
            'name' => ['required', 'string', 'max:255', 'unique:travels'],
            'description' => ['required'],
            'num_of_days' => ['required', 'integer'],
        ];
    }
}
