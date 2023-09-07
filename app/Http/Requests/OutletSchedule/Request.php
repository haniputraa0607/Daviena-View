<?php

namespace App\Http\Requests\OutletSchedule;

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
            //
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'all_products' => (bool)$this->all_products,
            'is_closed' => (int)$this->is_closed,
        ]);
    }
}
