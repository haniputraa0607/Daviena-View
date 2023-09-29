<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiProductPackageController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        $product = $product->where('type', 'Package');
        return $this->ok('success', $product->get());
    }

    public function list(Request $request): JsonResponse
    {
        $query = Product::where('type', 'Package');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('product_package.detail', ['id' => $row->id]) . '">
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
        $payload = [
            'product_name' => $request->product_name,
            'description' => $request->description,
            'product_code' => $request->product_code,
            'type' => 'Package',
            'is_active' => 1,
        ];
        $product_group = [];
        foreach ($request->product as $key) {
            $product_group[] = [
                'id' => $key,
            ];
        }
        if ($product_group) {
            $payload['product_groups'] = json_encode($product_group);
        }
        $product = Product::create($payload);
        return $this->ok("succes", $product);
    }
    public function update(ProductRequest $request, $id): JsonResponse
    {
        $payload = [
            'product_name' => $request->product_name,
            'description' => $request->description,
            'product_code' => $request->product_code,
        ];

        $product_group = [];
        foreach ($request->product as $key) {
            $product_group[] = [
                'id' => $key,
            ];
        }
        if ($product_group) {
            $payload['product_groups'] = json_encode($product_group);
        }
        $product = Product::find($id)->update($payload);
        return $this->ok("succes", $product);
    }

    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);

        $productGlobalPriceCount = ProductGlobalPrice::where('product_id', $id)->count();
        if ($productGlobalPriceCount > 0) {
            return $this->error('The product is still connected to the global price');
        }
        $product->delete();
        return $this->ok("succes", true);
    }
}
