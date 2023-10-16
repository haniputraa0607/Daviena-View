<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\MyHelper;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiOrderController extends Controller
{
    public function list(Request $request)
    {
        $query = Order::query();
        if ($request->outlet_id) {
            $query->where('outlet_id', $request->input('outlet_id'));
        }
        if ($request->start_date && $request->end_date) {
            $query->whereDate('order_date', '>=', $request->input('start_date'));
            $query->whereDate('order_date', '<=', $request->input('end_date'));
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('order_grandtotal', function ($row) {
                return MyHelper::rupiah($row->order_grandtotal);
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('transaction.order.detail', ['id' => $row->id]) . '">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>';
            })
            ->rawColumns(['order_grandtotal','action'])->make(true);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $product = Order::with('transactions', 'patient', 'outlet', 'chasier', 'order_product.product')->find($id);
        // dd($product->toSql());
        return $this->ok("success", $product);
    }
    // public function store(TreatmentRequest $request): JsonResponse
    // {
    //     $product = Product::create($request->all());
    //     return $this->ok("succes", $product);
    // }
    // public function update(TreatmentRequest $request, Product $product): JsonResponse
    // {
    //     $product->update($request->all());
    //     return $this->ok("succes", $product);
    // }
    // public function destroy($id): JsonResponse
    // {
    //     $global_price = ProductGlobalPrice::where(['product_id' => $id])->delete();
    //     $product = Product::where(['id' => $id])->delete();
    //     return $this->ok("succes", $product);
    // }
}
