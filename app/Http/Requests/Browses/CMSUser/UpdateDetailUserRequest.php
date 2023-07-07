<?php

namespace App\Http\Requests\Browses\CMSUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailUserRequest extends FormRequest
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
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email:rfc,dns',
            'is_active'             => 'nullable|string|in:1,0',
            'admin_role'            => 'nullable|string',
            'super_admin_password'  => 'required|string|min:8',
        ];
    }

    protected function getRedirectUrl()
    {
        $fragment = '#tab_detail';
        return parent::getRedirectUrl() .$fragment;
    }
}
