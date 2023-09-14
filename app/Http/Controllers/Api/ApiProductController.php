<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiProductController extends Controller
{

    public function index(Product $product): JsonResponse
    {
        return $this->ok('success', $product->all());
    }

    public function list(Request $request): JsonResponse
    {
        $query = Product::where('type', $request->type);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('product.detail', ['id' => $row->id]) . '">
                            <li class="fa fa-search" aria-hidden="true"></li>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <li class="fa fa-trash" aria-hidden="true"></li>
                        </a>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $product = Product::with('global_price')->find($id);
        return $this->ok("success", $product);
    }
    public function store(ProductRequest $request): JsonResponse
    {
        $product = Product::create($request->all());
        return $this->ok("succes", $product);
    }
    public function update(ProductRequest $request, Product $product): JsonResponse
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
