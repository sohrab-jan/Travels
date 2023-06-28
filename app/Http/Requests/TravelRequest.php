<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'is_public' => ['boolean'],
            'name' => ['required', 'string', 'unique:travels'],
            'description' => ['required'],
            'number_of_days' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
