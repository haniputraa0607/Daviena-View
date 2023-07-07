<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\VersionControl\UpdateVersionControlRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\MyHelper;

class VersionControlController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'version_control';

    public function getVersionControllSetting()
    {
        $data = [ 'title'             => 'Version Control',
                  'menu_active'       => 'setting-version',
                  'submenu_active'    => 'setting-version'
                ];

        $version = MyHelper::get(self::SOURCE,'v1/version');

        if (isset($version['status']) && $version['status'] == "success") {
            $result = $version['data'];
            $data['version'] = [
                "version_appstore" => $result['version_appstore_url'],
                "version_max_ios" => $result['version_ios_max'],
                "version_playstore" => $result['version_playstore_url'],
                "version_max_android" => $result['version_android_max'],
                "version_text_alert_mobile"=> $result['version_text_alert'],
                "version_text_button_mobile" => $result['version_text_button'],
                "version_image_mobile" => $result['version_image'],
                "Android" => MyHelper::sortVersions($result['android_version_list']),
                "IOS" => MyHelper::sortVersions($result['ios_version_list']),
            ];
        } else {
            $data['version'] = ['Android' => [], 'IOS' => []];
        }

        return view('settings.version-control.setting-version', $data);
    }

    public function updateVersionControllSetting(UpdateVersionControlRequest $request)
    {
        $payload = [];
        if ($request->Android) {
            $allowed_versions = 0;
            $payload['version_playstore_url'] = $request->Android['version_playstore'];
            $payload['version_android_max'] = $request->Android['version_max_android'];
            foreach ($request->Android['version'] as $key => $value) {
                switch ($value['rules']) {
                    case 1:
                        $value['rules'] = true;
                        $allowed_versions++;
                        break;

                    case 0:
                        $value['rules'] = false;
                        break;
                }
                $payload['android_version_list'][] = $value;
            }

            if ($allowed_versions > $payload['version_android_max']) {
                return back()->withErrors('Total allowed versions are greater than max supported version')->withInput();
            }
            $redirect_tab = '';
        } else if ($request->IOS) {
            $allowed_versions = 0;
            $payload['version_appstore_url'] = $request->IOS['version_appstore'];
            $payload['version_ios_max'] = $request->IOS['version_max_ios'];
            foreach ($request->IOS['version'] as $key => $value) {
                switch ($value['rules']) {
                    case 1:
                        $value['rules'] = true;
                        $allowed_versions++;
                        break;

                    case 0:
                        $value['rules'] = false;
                        break;
                }
                $payload['ios_version_list'][] = $value;
            }
            if ($allowed_versions > $payload['version_ios_max']) {
                return back()->withFragment('#tab_IOS')->withErrors('Total allowed versions are greater than max supported version')->withInput();
            }
            $redirect_tab = '#tab_IOS';
        } else if ($request->Display) {
            $payload['version_text_alert'] = $request->Display['version_text_alert_mobile'];
            $payload['version_text_button'] = $request->Display['version_text_button_mobile'];

            if (isset($request->Display['version_image_mobile'])) {
                $path = 'image';
                $fileName = MyHelper::createFilename($request->Display['version_image_mobile']);
                Storage::deleteDirectory(self::ROOTPATH . '/' . $path); //Delete existing image first
                $uploadedFile = MyHelper::uploadFile($request->Display['version_image_mobile'], self::ROOTPATH, $path, $fileName);

                $payload['version_image'] = $uploadedFile['path'];
            }
            $redirect_tab = '#tab_display_androidios';
        } else {
            return back()->withFragment('#tab_display_androidios')->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $save = MyHelper::post(self::SOURCE,'v1/version', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/version')->withFragment($redirect_tab)->withSuccess(['Version Setting has been updated.']);
        } else {
            if (isset($save['errors'])) {
                return back()->withFragment($redirect_tab)->withErrors($save['errors'])->withInput();
            }
            if (isset($save['status']) && $save['status'] == "fail") {
                return back()->withFragment($redirect_tab)->withErrors($save['message'])->withInput();
            }
            return back()->withFragment($redirect_tab)->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
