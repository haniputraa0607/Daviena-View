<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private string $path = 'be/banner/';
    public function index()
    {
        $data = [
            'title'             => 'Manage Banner',
            'sub_title'         => 'List',
            'menu_active'       => 'banner',
        ];
        $banner = MyHelper::get($this->path);
        if (isset($banner['status']) && $banner['status'] == "success") {
            $data['banners'] = $banner['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.banner.index', $data);
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Banner',
            'sub_title'         => 'Detail',
            'menu_active'       => 'banner',
        ];
        $banner = MyHelper::get($this->path . $id);
        $products = MyHelper::get('be/product/');
        if (isset($banner['status']) && $banner['status'] == "success") {
            $data['detail'] = $banner['result'];
            $data['products'] = $products['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.banner.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->except('token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('banner')->withSuccess(['CMS Banner detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteBanner($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Product deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
