<?php

namespace App\Http\Requests\Browses\Location;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationDeveloperRequest extends FormRequest
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
            'name' => 'required|string',
            'code' => 'required|string|max:3|min:3'
        ];
    }
}
