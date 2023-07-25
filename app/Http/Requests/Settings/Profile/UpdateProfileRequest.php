<?php

namespace App\Http\Requests\Settings\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        if ($this->has('name')) {
            $rules = [
                'id' => 'required|string',
                'name' => 'required|string|max:100',
                'email' => 'required|email:rfc,dns',
            ];
        } elseif ($this->has('old_password')) {
            $rules = [
                'id' => 'required|string',
                'old_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*\d).+$/',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'new_password.regex' => "The new password must contain at least one number."
        ];
    }

    protected function getRedirectUrl()
    {
        if ($this->has('name')) {
            $fragment = '#tab_detail';
        } elseif ($this->has('old_password')) {
            $fragment = '#tab_password';
        } else {
            $fragment = '';
        }
        return parent::getRedirectUrl() . $fragment;
    }
}
