<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Treatment',
            'sub_title'         => 'List',
            'menu_active'       => 'treatment',
        ];
        $treatment = MyHelper::get('be/product?type=treatment');
        if (isset($treatment['status']) && $treatment['status'] == "success") {
            $data['treatments'] = $treatment['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.treatment.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Treatment',
            'sub_title'         => 'List',
            'menu_active'       => 'treatment',
        ];
        $category = MyHelper::get('be/product-category');
        if (isset($category['status']) && $category['status'] == 'success') {
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.treatment.create', $data);
    }

    public function store(Request $request)
    {
        $payload = [
            "product_name" => $request->product_name,
            "type" => 'Treatment',
            "product_code" => $request->product_code,
            "price" => $request->price,
            "description"  => $request->description,
            "is_active" => 1,
            "need_recipe_status" => 1
        ];
        if ($request->hasFile('image')) {
            $name_file = $request->file('image')->getClientOriginalName();
            $path = "img/product/";
            $request->file('image')->move($path, $name_file);
            $payload['image'] = $path.$name_file;
        }
        $save = MyHelper::post('be/product', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('treatment')->withSuccess(['New Treatment successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Treatment',
            'sub_title'         => 'Detail',
        ];
        $treatment = MyHelper::get('be/product/' . $id);
        $category = MyHelper::get('be/product-category');
        if (isset($treatment['status']) && $treatment['status'] == "success" && isset($category['status']) && $category['status'] == 'success') {
            $data['detail'] = $treatment['result'];
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.treatment.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = [
            "product_name"              => $request->product_name,
            "price"                     => $request->price,
            "product_code"              => $request->product_code,
            "type"                      => 'Treatment',
            "description"               => $request->description,
        ];
        if ($request->hasFile('image')) {
            $name_file = $request->file('image')->getClientOriginalName();
            $path = "img/product/";
            $request->file('image')->move($path, $name_file);
            $payload['image'] = $path.$name_file;
        }   
        $save = MyHelper::patch('be/product/' . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('treatment')->withSuccess(['CMS Treatment detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteTreatment($id)
    {
        $delete = MyHelper::deleteApi('be/product/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Treatment deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
