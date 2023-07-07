<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PrivacyPolicy\UpdatePrivacyPolicyRequest;
use Illuminate\Http\Request;
use App\Lib\MyHelper;

class PrivacyPolicyController extends Controller
{
    const SOURCE = 'core-api';

    public function getPrivacyPolicySetting()
    {
        $data = [
                    'title'             => 'Privacy Policy',
                    'menu_active'       => 'privacy-policy',
                    'submenu_active'    => 'privacy-policy',
                ];


        $setting = MyHelper::get(self::SOURCE,'v1/setting/privacy_policy');

        if (isset($setting['status']) && $setting['status'] == 'success') {
            $result = $setting['data'];
            $data['value'] = $result['value_text'];
        } else {
            return view('settings.privacy-policy.privacy-policy-setting', $data)->withErrors($setting['message']);
        }

        return view('settings.privacy-policy.privacy-policy-setting', $data);
    }

    public function updatePrivacyPolicySetting(UpdatePrivacyPolicyRequest $request)
    {
        $payload = [
            'value_text' => $request->value
        ];

        $save = MyHelper::post(self::SOURCE,'v1/setting/privacy_policy/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/privacy-policy')->withSuccess(['Privacy Policy Setting has been updated.']);
        } else {
            if (isset($save['errors'])) {
                return back()->withErrors($save['errors'])->withInput();
            }
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
