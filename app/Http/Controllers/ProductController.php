<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private string $path = 'be/product/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Product',
            'sub_title'         => 'List',
            'menu_active'       => 'product',
        ];
        // $product = MyHelper::get('be/product?type=product');
        // dd($product);
        return view('pages.product.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Product',
            'sub_title'         => 'List',
            'menu_active'       => 'product',
        ];
        $category = MyHelper::get('be/product-category');
        if (isset($category['status']) && $category['status'] == 'success') {
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.product.create', $data);
    }

    public function store(Request $request)
    {

        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('product')->withSuccess(['New Product successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Product',
            'sub_title'         => 'Detail',
        ];
        $product = MyHelper::get($this->path . $id);
        $category = MyHelper::get('be/product-category');

        if (isset($product['status']) && $product['status'] == "success" && isset($category['status']) && $category['status'] == 'success') {
            $data['detail'] = $product['result'];
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.product.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('product')->withSuccess(['CMS Product detail has been updated.']);
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
