<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class BannerClinicController extends Controller
{
    private string $path = 'be/banner_clinic/';
    public function index()
    {
        $data = [
            'title'             => 'Manage Banner Clinic',
            'sub_title'         => 'List',
            'menu_active'       => 'banner_clinic',
        ];
        return view('pages.landing_page.banner_clinic.index', $data);
    }

    public function store(Request $request)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            $folder_image = 'articles';
            $upload = MyHelper::uploadImageApi($image, $folder_image);
            if (isset($upload['status']) && $upload['status'] == "success") {
                $payload = [
                    'image' => $upload['result']
                ];
                $save = MyHelper::post($this->path . 'store', $payload);
                dd($save);
                if (isset($save['status']) && $save['status'] == "success") {
                    return redirect('landing_page/banner_clinic')->withSuccess(['Success banner uploaded.']);
                } else {
                    return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
                }
            } elseif (isset($upload['status']) && $upload['status'] == 'fail') {
                return back()->withErrors($upload['messages'])->withInput();
            }
        } else {
            return back()->withErrors(['Image has not been selected.'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            $folder_image = 'articles';
            $upload = MyHelper::uploadImageApi($image, $folder_image);
            if (isset($upload['status']) && $upload['status'] == "success") {
                $payload = [
                    'image' => $upload['result']
                ];
                $save = MyHelper::patch($this->path . $id, $payload);
                if (isset($save['status']) && $save['status'] == "success") {
                    return redirect('landing_page/banner_clinic')->withSuccess(['Success banner uploaded.']);
                } else {
                    return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
                }
            } elseif (isset($upload['status']) && $upload['status'] == 'fail') {
                return back()->withErrors($upload['messages'])->withInput();
            }
        } else {
            return back()->withErrors(['Image has not been selected.'])->withInput();
        }
    }

    public function deleteBannerClinic($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['CMS Banner Clinic deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
