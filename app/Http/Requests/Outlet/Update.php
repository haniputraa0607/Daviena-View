<?php

namespace App\Http\Requests\Outlet;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'name' => 'required',
            'id_partner' => 'required',
            'outlet_code' => 'required',
            'outlet_phone' => 'required|numeric',
            'outlet_email' => 'required',
            'status' => 'required',
            // 'is_tax' => 'required',
            'address' => 'required',
            'district_code' => 'required|exists:indonesia_districts,code',
            // 'postal_code' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'activities' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'outlet_phone' => $this->phone,
            'id_partner' =>  $this->partner,
            'outlet_email' => $this->email,
            'coordinates' => json_encode([
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
            ]),
            'activities' => json_encode($this->activities),
        ]);
    }
}
