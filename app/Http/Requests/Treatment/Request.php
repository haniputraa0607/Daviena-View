<?php

namespace App\Http\Requests\Treatment;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "product_name"  => 'required',
            "product_code"  => 'required',
            "description"   => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'type' => 'Treatment'
        ]);
    }
}
