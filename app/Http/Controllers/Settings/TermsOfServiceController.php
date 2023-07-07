<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TermsOfService\UpdateTOSRequest;
use Illuminate\Http\Request;
use App\Lib\MyHelper;

class TermsOfServiceController extends Controller
{
    const SOURCE = 'core-api';

    public function getTermsOfServiceSetting()
    {
        $data = [
                    'title'             => 'Terms of Service',
                    'menu_active'       => 'terms-of-service',
                    'submenu_active'    => 'terms-of-service',
                ];

        $setting = MyHelper::get(self::SOURCE,'v1/setting/terms_of_service');

        if (isset($setting['status']) && $setting['status'] == 'success') {
            $result = $setting['data'];
            $data['value'] = $result['value_text'];
        } else {
            return view('settings.terms-of-service.terms-of-service-setting', $data)->withErrors($setting['message']);
        }

        return view('settings.terms-of-service.terms-of-service-setting', $data);
    }

    public function updateTermsOfServiceSetting(UpdateTOSRequest $request)
    {
        $payload = [
            'value_text' => $request->value
        ];

        $save = MyHelper::post(self::SOURCE,'v1/setting/terms_of_service/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/terms-of-service')->withSuccess(['Terms Of Service Setting has been updated.']);
        } else {
            if (isset($save['errors'])) {
                return back()->withErrors($save['errors'])->withInput();
            }
            if (isset($save['status']) && $save['status'] == "fail") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
