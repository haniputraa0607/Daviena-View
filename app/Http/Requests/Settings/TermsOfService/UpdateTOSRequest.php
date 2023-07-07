<?php

namespace App\Http\Requests\Settings\TermsOfService;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTOSRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'value' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'value.required' => 'Terms Of Service tidak boleh kosong!'
        ];
    }
}
