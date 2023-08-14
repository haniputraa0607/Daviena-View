<?php

namespace App\Http\Requests\Grievance;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'grievance_name' => 'required',
            'description' => 'required',
            'is_active' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->is_active ?? 0
        ]);
    }
}
