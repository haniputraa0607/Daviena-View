<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiProductCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $product = $request->length ?  ProductCategory::paginate($request->length ?? 10) : ProductCategory::get();
        return $this->ok("success get data all users", $product);
    }

    public function show(ProductCategory $product_category): JsonResponse
    {
        return $this->ok("success", $product_category);
    }
    public function store(Request $request): JsonResponse
    {
        $user = ProductCategory::create($request->all());
        return $this->ok("succes", $user);
    }
    public function update(Request $request, ProductCategory $product_category): JsonResponse
    {
        $product_category->update($request->all());
        return $this->ok("succes", $product_category);
    }
    public function destroy(ProductCategory $product_category): JsonResponse
    {
        $product_category->delete();
        return $this->ok("succes", $product_category);
    }
}
