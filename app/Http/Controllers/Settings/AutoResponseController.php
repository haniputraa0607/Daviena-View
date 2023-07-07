<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\AutoResponse\UpdateAutoResponseRequest;
use App\Lib\MyHelper;

class AutoResponseController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'auto-response';

    public function getAutoResponseSetting() {
        $data = [
            'title'   => 'Auto-Response',
            'sub_title'   => 'List',
            'menu_active'    => 'setting-autoresponse',
            'submenu_active' => 'setting-autoresponse'
        ];

        $auto_responses = MyHelper::get(self::SOURCE, 'v1/auto-response');

        if (isset($auto_responses['status']) && $auto_responses['status'] == "success") {
            $data['auto_responses'] = $auto_responses['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('settings.auto-response.autoresponse_list', $data);
    }

    public function getAutoResponseDetail($code) {
        $data = [
            'title'   => 'Auto-Response',
            'sub_title'   => 'Detail',
            'menu_active'    => 'setting-autoresponse',
            'submenu_active' => 'setting-autoresponse'
        ];

        $auto_response = MyHelper::get(self::SOURCE, 'v1/auto-response/'.$code);

        if (isset($auto_response['status']) && $auto_response['status'] == "success") {
            $data['detail'] = $auto_response['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('settings.auto-response.autoresponse_detail', $data);
    }

    public function updateAutoResponse(UpdateAutoResponseRequest $request) {
        $logo_path = "";
        if (!empty($request->push_image)) {
            $path = 'push-image/'.$request->code;
            $fileName = MyHelper::createFilename($request->push_image);

            $uploadedFile = MyHelper::uploadFile($request->push_image, self::ROOTPATH, $path, $fileName);

            if ($uploadedFile['path'] == null) {
                return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
            }

            //Delete image file that deleted images from database
            MyHelper::deleteImageNotExist('auto-response/push-image/'.$request->code, [$uploadedFile['path']]);
            $logo_path = $uploadedFile['path'];
        }

        $payload = [
            "code"                  => $request->code,
            "email_toggle"          => $request->email_toggle == "1" ? true : false,
            "email_subject"         => $request->email_subject ?? "",
            "email_content"         => $request->email_content ?? "",
            "push_toggle"           => $request->push_toggle == "1" ? true : false,
            "push_subject"          => $request->push_subject ?? "",
            "push_content"          => $request->push_content ?? "",
            "push_click_to"         => $request->push_click_to ?? "",
            "push_image"            => $logo_path,
            "forward_toggle"        => $request->forward_toggle == "1" ? true : false,
            "forward_email"         => $request->forward_email ?? "",
            "forward_email_subject" => $request->forward_email_subject ?? "",
            "forward_email_content" => $request->forward_email_content ?? ""
        ];

        $save = MyHelper::post(self::SOURCE,'v1/auto-response/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Auto-Response updated successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
