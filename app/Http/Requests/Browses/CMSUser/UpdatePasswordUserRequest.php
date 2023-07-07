<?php

namespace App\Http\Requests\Browses\CMSUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordUserRequest extends FormRequest
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
            'id'                    => 'required|string',
            'super_admin_password'  => 'required|string|min:8',
            'new_password'          => 'required|string|min:8|confirmed|regex:/^(?=.*\d).+$/',
        ];
    }

    public function messages()
    {
        return [
            'new_password.regex' => "The new password must contain at least one number."
        ];
    }

    protected function getRedirectUrl()
    {
        $fragment = '#tab_password';
        return parent::getRedirectUrl() .$fragment;
    }
}
