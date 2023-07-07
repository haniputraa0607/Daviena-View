<?php

namespace App\Http\Requests\Browses\CMSUser;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            "role"      => "nullable|string|in:admin,super_admin",
            "name"      => 'required|string|max:100',
            "email"     => 'required|email:rfc,dns',
            "password"  => 'required|string|min:8|confirmed|regex:/^(?=.*\d).+$/'
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => "The password must contain at least one number."
        ];
    }
}
