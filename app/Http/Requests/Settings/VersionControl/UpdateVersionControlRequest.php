<?php

namespace App\Http\Requests\Settings\VersionControl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVersionControlRequest extends FormRequest
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
        if ($this->has('Android')) {
            $rules = [
                'Android.version' => 'required|array|min:1',
                'Android.version.*.id' => 'nullable|string',
                'Android.version.*.app_version' => 'required|distinct|string|regex:/^\d+\.\d+\.\d+$/',
                'Android.version.*.rules' => 'required|string',
                'Android.version_playstore' => 'required|string',
                'Android.version_max_android' => 'required|integer',
            ];
        } elseif ($this->has('IOS')) {
            $rules = [
                'IOS.version' => 'required|array|min:1',
                'IOS.version.*.id' => 'nullable|string',
                'IOS.version.*.app_version' => 'required|distinct|string|regex:/^\d+\.\d+\.\d+$/',
                'IOS.version.*.rules' => 'required|string',
                'IOS.version_appstore' => 'required|string',
                'IOS.version_max_ios' => 'required|integer',
            ];
        } elseif ($this->has('Display')) {
            $rules = [
                'Display.version_text_alert_mobile' => 'required|string|max:150',
                'Display.version_text_button_mobile' => 'required|string|max:20',
                'Display.version_image_mobile' => 'nullable|mimes:png,jpg|max:5120',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return[
            'Android.version' => 'Version Rule',
            'Android.version_playstore' => 'Playstore Link',
            'Android.version_max_android' => 'Max Android Version',
            'Android.version.*.id' => 'Android Version ID',
            'Android.version.*.app_version' => 'Android Version',
            'Android.version.*.rules' => 'Android Version Rule',
            'IOS.version' => 'Version Rule',
            'IOS.version.*.id' => 'iOS Version ID',
            'IOS.version.*.app_version' => 'iOS Version',
            'IOS.version.*.rules' => 'iOS Version Rule',
            'IOS.version_appstore' => 'Appstore Link',
            'IOS.version_max_ios' => 'Max iOS Version',
            'Display.version_text_alert_mobile' => 'Text Alert',
            'Display.version_text_button_mobile' => 'Text Button',
            'Display.version_image_mobile' => 'Image',
        ];

    }

    protected function getRedirectUrl()
    {
        if ($this->has('IOS')) {
            $fragment = '#tab_IOS';
        } elseif ($this->has('Display')) {
            $fragment = '#tab_display_androidios';
        } else {
            $fragment = '';
        }
        return parent::getRedirectUrl() .$fragment;
    }
}
