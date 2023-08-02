<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Product',
            'sub_title'         => 'List',
            'menu_active'       => 'product',
        ];
        $product = MyHelper::get('be/product');
        if (isset($product['status']) && $product['status'] == "success") {
            $data['products'] = $product['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
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
        $payload = [
            "product_name" => $request->product_name,
            "type"       => $request->type,
            "product_code" => $request->product_code,
            "price" => $request->price,
            "product_category_id"  => $request->product_category_id,
            "description"  => $request->description,
            "is_active" => 1,
            "need_recipe_status" => 1
        ];
        $save = MyHelper::post('be/product', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('product')->withSuccess(['New Product successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Product',
            'sub_title'         => 'Detail',
        ];
        $product = MyHelper::get('be/product/' . $id);
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
        $payload = [
            "product_name"              => $request->product_name,
            "product_category_id"       => $request->product_category_id,
            "price"                     => $request->price,
            "product_code"              => $request->product_code,
            "type"                      => $request->type,
            "description"               => $request->description,
        ];
        $save = MyHelper::patch('be/product/' . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('product')->withSuccess(['CMS Product detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteProduct($id)
    {
        $delete = MyHelper::deleteApi('be/product/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Product deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
