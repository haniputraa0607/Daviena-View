<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
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
        return  [
            'equal_id' => 'required|numeric',
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'idc' => 'required|unique:users,idc',
            'birthdate' => 'required',
            'phone' => 'required|numeric',
            'gender' => 'required|in:Male,Female',
            'district_code' => 'required|exists:indonesia_districts,code',
            'consultation_price' => 'required_if:type,salesman',
            'type' => 'required',
            'level' => 'required',
            'outlet_id' => 'required',
            'address' => 'required|min:6',
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'gender' => ucfirst($this->gender),
        ]);
    }
}
