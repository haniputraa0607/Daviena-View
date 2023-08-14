<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Treatment\Request as TreatmentRequest;
use App\Models\Product;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiTreatmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $type = $request->type ? $request->type : '';
        if ($request->length) {
            $product = Product::when($type, function ($q) use ($type) {
                $q->where('type', $type);
            })->paginate($request->length ?? 10);
        } else {
            $product = Product::when($type, function ($query) use ($type) {
                return $query->where(['type' => $type]);
            })->get();
        }
        return $this->ok("success get data all users", $product);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $product = Product::with('global_price')->find($id);
        return $this->ok("success", $product);
    }
    public function store(TreatmentRequest $request): JsonResponse
    {
        $product = Product::create($request->all());
        return $this->ok("succes", $product);
    }
    public function update(TreatmentRequest $request, Product $product): JsonResponse
    {
        $product->update($request->all());
        return $this->ok("succes", $product);
    }
    public function destroy($id): JsonResponse
    {
        $global_price = ProductGlobalPrice::where(['product_id' => $id])->delete();
        $product = Product::where(['id' => $id])->delete();
        return $this->ok("succes", $product);
    }
}
