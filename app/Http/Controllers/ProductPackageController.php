<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class ProductPackageController extends Controller
{
    private string $path = 'be/product_package/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Product Package',
            'sub_title'         => 'List',
            'menu_active'       => 'product_package',
        ];
        return view('pages.product_package.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Product Package',
            'sub_title'         => 'List',
            'menu_active'       => 'product_package',
        ];
        $category = MyHelper::get('be/product-category');
        $product = MyHelper::get('be/product');
        if (isset($category['status']) && $category['status'] == 'success' && isset($product['status']) && $product['status'] == 'success') {
            $data['categorys'] = $category['result'];
            $data['products'] = $product['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.product_package.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('product_package')->withSuccess(['New Product Package successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Product Package',
            'sub_title'         => 'Detail',
        ];
        $detail = MyHelper::get($this->path . $id);
        // dd($detail);
        $category = MyHelper::get('be/product-category');
        $product = MyHelper::get('be/product');

        if (isset($product['status']) && $product['status'] == "success" && isset($category['status']) && $category['status'] == 'success' && isset($product['status']) && $product['status'] == 'success') {
            $data['detail'] = $detail['result'];
            $data['products'] = $product['result'];
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.product_package.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('product_package')->withSuccess(['CMS Product detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteProduct($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Product deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
