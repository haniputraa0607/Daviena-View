<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'required',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required',
            'phone' => [
                'required',
                Rule::unique('customers', 'phone')->ignore($this->customer, 'id')
            ],
            'email' => [
                'required',
                Rule::unique('customers', 'email')->ignore($this->customer, 'id')
            ],
            'is_active' => 'required',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->is_active ? 1 : 0
        ]);
    }
}
