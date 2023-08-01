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
        return view('pages.product.index', $data);
    }

    public function list(Request $request){
        $products = MyHelper::post('be/product/table_list', $request->all());
        return response()->json($products);
    }

    public function create()
    {
        $districts = MyHelper::get('indonesia/districts');
        $data = [
            'title'             => 'Create Product',
            'sub_title'         => 'List',
            'menu_active'       => 'product',
            'districts'         => $districts['data'],
        ];
        
        $category = MyHelper::get('be/product-category/list');
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
        $save = MyHelper::post('be/product/create', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('outlet')->withSuccess(['New Product successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $districts = MyHelper::get('indonesia/districts');
        $data = [
            'title'             => 'CMS Detail Product',
            'sub_title'         => 'Detail',
            'districts'         => $districts['data'],
        ];

        $product = MyHelper::post('be/product/detail', [
            'id' => $id
        ]);
        $category = MyHelper::get('be/product-category/list');

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

        $save = MyHelper::post('be/product/update/' . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('outlet')->withSuccess(['CMS Outlet detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteProduct($id)
    {
        $delete = MyHelper::deleteApi('be/product/delete/' . $id);

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Product deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
