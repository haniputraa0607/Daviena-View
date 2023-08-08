<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiBannerController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Banner::with(['product:id,product_name,description,product_category_id', 'product.product_category:id,product_category_name'])->all();
        return $this->ok('Banner List', $data);
    }

    public function show($id): JsonResponse
    {
        $data = Banner::with(['product:id,product_name,description,product_category_id', 'product.product_category:id,product_category_name'])->firstOrFail($id);
        return $this->ok('Banner Show', $data);
    }

    public function update(BannerRequest $request, $id): JsonResponse
    {
        $data = Banner::firstOrFail($id);
        $data->update($request->all());
        return $this->ok('Banner Updated', $data);
    }
}
