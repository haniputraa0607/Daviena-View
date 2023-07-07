<?php

namespace App\Http\Requests\Transactions\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentMethodLogoRequest extends FormRequest
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
            'id' => 'required|string',
            'file' => 'required|mimes:png,jpg|max:5120'
        ];
    }

    public function messages()
    {
        return [
            "file.max" => "Gambar tidak boleh lebih dari 5MB!",
        ];
    }

    public function attributes()
    {
        return[
            'file' => 'Gambar',
        ];

    }
}
