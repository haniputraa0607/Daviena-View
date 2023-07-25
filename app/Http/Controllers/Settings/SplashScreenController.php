<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SplashScreen\UpdateSplashScreenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\MyHelper;

class SplashScreenController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'splash_screen';

    public function getSplashScreenSetting()
    {
        $data = [
            'title'         => 'Mobile Apps Home Setting',
            'menu_active'    => 'splash-screen',
            'submenu_active' => 'splash-screen'
        ];

        $splash_screen = MyHelper::get(self::SOURCE, 'v1/splash-screen');

        if (isset($splash_screen['status']) && $splash_screen['status'] == "success") {
            $result = $splash_screen['data'];
            $data['default_home'] = [
                "default_home_splash_duration" => $result['default_home_splash_duration'],
                "default_home_splash_screen" => $result['default_home_splash_screen'],
                "signature_key" => $result['signature_key'],
            ];
        } else {
            $data['default_home'] = ['default_home_splash_screen' => null, 'default_home_splash_duration' => null];
            if (isset($splash_screen['status']) && $splash_screen['status'] == "error") {
                return back()->withErrors($splash_screen['message'])->withInput();
            }
        }

        return view('settings.splash-screen.setting-splash-screen', $data);
    }

    public function updateSplashScreenSetting(UpdateSplashScreenRequest $request)
    {
        $payload['default_home_splash_duration'] = $request->default_home_splash_duration;

        if (isset($request->default_home_splash_screen)) {
            $path = 'media';
            Storage::deleteDirectory(self::ROOTPATH . '/' . $path); //Delete existing media first
            $uploadedFile = MyHelper::uploadFile($request->file('default_home_splash_screen'), self::ROOTPATH, $path, "splash_screen");

            $payload['default_home_splash_screen'] = $uploadedFile['path'];
        } else {
            $payload['default_home_splash_screen'] = "";
        }

        $save = MyHelper::post(self::SOURCE, 'v1/splash-screen', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/splash-screen')->withSuccess(['Splash screen has been updated.']);
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
