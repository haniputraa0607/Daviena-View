<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Banner',
            'sub_title'         => 'List',
            'menu_active'       => 'Banner',
        ];
        $banner = MyHelper::get('be/banner');
        if (isset($banner['status']) && $banner['status'] == "success") {
            $data['banners'] = $banner['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.banner.index', $data);
    }

    public function create()
    {
        return view('pages.banner.create', [
            'title'             => 'Create Banner',
            'sub_title'         => 'List',
            'menu_active'       => 'Banner',
        ]);
    }

    public function store(Request $request)
    {
        $payload = [
            "title" => $request->title,
            "writer" => $request->writer,
            "release_date" => $request->release_date,
            "description" => $request->description,
        ];
        if ($request->hasFile('image')) {
            $name_file = $request->file('image')->getClientOriginalName();
            $path = public_path('\images');
            $request->file('image')->move($path, $name_file);
            $payload['image'] = $name_file;
        }

        $save = MyHelper::post('be/banner', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('banner')->withSuccess(['New Banner successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Banner',
            'sub_title'         => 'Detail',
        ];
        $banner = MyHelper::get('be/banner/' . $id);
        $products = MyHelper::get('be/product/');
        if (isset($banner['status']) && $banner['status'] == "success") {
            $data['detail'] = $banner['result'];
            $data['products'] = $products['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
// dd($data);
        return view('pages.banner.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = [
            "title" => $request->title,
            "product_id" => $request->product_id,
        ];

        $save = MyHelper::patch('be/banner/' . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('banner')->withSuccess(['CMS Banner detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteBanner($id)
    {
        $delete = MyHelper::deleteApi('be/banner/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Product deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
