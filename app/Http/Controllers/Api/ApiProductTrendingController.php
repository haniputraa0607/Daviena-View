<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\ProductTrending;
use App\Models\ProductPackage;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiProductTrendingController extends Controller
{

    public function index(ProductTrending $product_trending): JsonResponse
    {
        return $this->ok('success', $product_trending->all());
    }

    public function update(Request $request): JsonResponse
    {
        ProductTrending::truncate();
        foreach($request->product as $key){
            ProductTrending::create([
                'product_id' => $key
            ]);
        }
        return $this->ok("succes", true);
    }
}
