<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Settings\OnBoarding\UpdateOnboardingRequest;
use App\Http\Requests\Settings\OnBoarding\AddOnboardingImageRequest;
use App\Lib\MyHelper;
use Exception;

class OnboardingController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'onboarding';

    public function getOnboardingSetting()
    {
        $data = [
            'title'          => 'Setting',
            'menu_active'    => 'on-boarding',
            'submenu_active' => 'on-boarding',
            'sub_title'      => 'On-Boarding Setting',
        ];

        $on_boarding = MyHelper::get(self::SOURCE,'v1/on-boarding');

        $existing_file = [];
        foreach ($on_boarding['data']['image'] as $value) {
            array_push($existing_file, $value['path']);
        }

        //Delete image file that deleted images from database
        MyHelper::deleteImageNotExist('onboarding/image', $existing_file);

        if (isset($on_boarding['status']) && $on_boarding['status'] == "success") {
            $data['setting'] = $on_boarding['data'];
        } else {
            if (isset($on_boarding['status']) && $on_boarding['status'] == "error") {
                return back()->withErrors($on_boarding['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('settings.on-boarding.onboarding', $data);
    }

    public function updateOnboardingSetting(UpdateOnboardingRequest $request)
    {
        $images = [];
        foreach ($request->image as $key => $id) {
            array_push($images, ['id' => $id['id'], 'order_no' => $key]);
        }

        $payload = [
            "onboarding_active" => !empty($request->active) ? true : false,
            "onboarding_skipable" => !empty($request->skipable) ? true : false,
            "onboarding_text_next" => $request->text_next,
            "onboarding_text_skip" => $request->text_skip,
            "onboarding_text_last" => $request->text_last,
            "images" => $images
        ];

        $save = MyHelper::post(self::SOURCE,'v1/on-boarding', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/on-boarding')->withSuccess(['On-Boarding setting has been updated.']);
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

    public function addOnboardingImage(AddOnboardingImageRequest $request)
    {
        try {
            $path = 'image';
            $fileName = MyHelper::createFilename($request->file);
            $uploadedFile = MyHelper::uploadFile($request->file, self::ROOTPATH, $path, $fileName);

            if ($uploadedFile['path'] != null) {
                $save['status'] = "success";
            }

            $payload = [
                "order_no" => (int)$request->order,
                "path"  => $uploadedFile['path']
            ];
        } catch (Exception $error) {
            return back()->withErrors($error->getMessage())->withInput();
        }

        $save = MyHelper::post(self::SOURCE,'v1/on-boarding/add', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/on-boarding')->withSuccess(['Onboarding image successfully added.']);
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
