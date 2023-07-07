<?php

namespace App\Http\Requests\Settings\OnBoarding;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOnboardingRequest extends FormRequest
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
            'active'        => 'nullable|string',
            'skipable'      => 'nullable|string',
            'text_next'     => 'required|string|max:11',
            'text_skip'     => 'required|string|max:11',
            'text_last'     => 'required|string|max:11',
            'image'         => 'required|array|min:1',
            'image.*.id'    => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            "image.required" => "Gambar On-Boarding tidak boleh kosong!",
            "image.*.id.required" => "Gambar On-Boarding minimal 1",
        ];
    }
}
