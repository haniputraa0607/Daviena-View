<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Treatment\Request as TreatmentRequest;
use App\Models\Product;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiTreatmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::where('type', 'Treatment');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('outlet.detail', ['id' => $row->id]) . '">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';
            })
            ->rawColumns(['action'])->make(true);
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
