<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\ProductFinest;
use App\Models\ProductFinestList;
use App\Models\ProductTrending;
use App\Models\ProductPackage;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiProductFinestController extends Controller
{
    public function index(): JsonResponse
    {
        $product_finest = ProductFinest::first();
        $product_finest['list'] = ProductFinestList::all();
        return $this->ok('success', $product_finest);
    }

    public function update(Request $request): JsonResponse
    {
        $productFinest = ProductFinest::find(1); // Ganti 1 dengan ID yang sesuai
        if (!$productFinest) {
            $productFinest->create([
                'title' => $request->title,
                'description' => $request->description
            ]);
        }

        $productFinest->update([
            'title' => $request->title,
            'description' => $request->description
        ]);
        ProductFinestList::truncate();
        foreach ($request->product as $key) {
            ProductFinestList::create([
                'product_id' => $key
            ]);
        }
        return $this->ok("succes", true);
    }
}
