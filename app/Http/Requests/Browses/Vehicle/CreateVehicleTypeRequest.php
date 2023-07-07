<?php

namespace App\Http\Requests\Browses\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleTypeRequest extends FormRequest
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
            "type_name" => "required|string|max:100",
            "type_visibility" => "nullable|string|in:1,0",
            "ac_max" => "nullable|numeric",
            "dc_max" => "nullable|numeric",
            "id" => "required|string"
        ];
    }
}
