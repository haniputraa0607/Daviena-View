<?php

namespace App\Http\Requests\Transactions\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvailablePaymentSettingRequest extends FormRequest
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
            "ids" => "required|array",
            "ids.*" => "required|string",
            'status' => "required|array|min:1",
            'status.*.status' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => "Atleast 1 payment method must be Enabled",
            'status.*.status.required' => "Atleast 1 payment method must be Enabled",
        ];
    }
}
