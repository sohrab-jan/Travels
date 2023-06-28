<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data_from' => ['date'],
            'data_to' => ['date'],
            'price_from' => ['numeric'],
            'price_to' => ['numeric'],
            'sort_by' => [Rule::in(['price'])],
            'sort_order' => [Rule::in(['desc', 'asc'])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'sort_by' => "The 'sort_by' parameter accepts only 'price' value",
            'sort_order' => "The 'sort_order' parameter accepts only 'asc' or 'desc' value",
        ];
    }
}
