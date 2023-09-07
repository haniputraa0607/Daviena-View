<?php

namespace App\Http\Requests\User;

use App\Lib\MyHelper;
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
        return !$this->new_password ?
            [
                'equal_id' => 'required|numeric|unique:users,equal_id,' . $this->id . ',id',

                'name' => 'required',
                'username' => 'required',
                'email' => 'required',
                'idc' => 'required|unique:users,idc,' . $this->id . ',id',
                'birthdate' => 'required',
                'phone' => 'required|',
                'gender' => 'required|in:Male,Female',
                'district_code' => 'required|exists:indonesia_districts,code',
                'type' => 'required',
                'consultation_price' => 'required_if:type,salesman',
                'level' => 'required',
                'outlet_id' => 'required',
                'address' => 'required|min:6',
            ] :
            [
                'password' => 'min:6',
                'password_confirmation' => 'required_with:password|same:password|min:6',
            ];
    }

    public function prepareForValidation()
    {
        $toMerge = !$this->new_password ?
            [
                'district_code' => $this->district_code ?? $this->distinct,
                'outlet_id' => $this->outlet_id ?? $this->outlet,
                'gender' => ucfirst($this->gender),
            ] :
            [];
        $this->merge($toMerge);
    }
}
