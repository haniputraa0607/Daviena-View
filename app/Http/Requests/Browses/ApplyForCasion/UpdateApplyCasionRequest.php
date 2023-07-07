<?php

namespace App\Http\Requests\Browses\ApplyForCasion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplyCasionRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{10,}$/',
            'location_name' => 'required|string|max:150',
            'location_address' => 'required|string|max:700',
            'relation_to_location' => 'required|string|in:owner,management,tenant,customer,other'
        ];
    }
}
