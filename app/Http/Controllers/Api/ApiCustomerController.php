<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Request as CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiCustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Customer::query();
        return DataTables::of($query)
            ->addIndexColumn()
            // ->addColumn('city', function ($row) {
            //     return $row->district->name;
            // })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('customer.detail', ['id' => $row->id]) . '">
                            <li class="fa fa-search" aria-hidden="true"></li>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <li class="fa fa-trash" aria-hidden="true"></li>
                        </a>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function nameId(Request $request): JsonResponse
    {
        return $this->ok("succes get customer", Customer::select('name', 'id')->get());
    }
    public function show(Customer $customer): JsonResponse
    {
        $customer;
        return $this->ok("succes", $customer);
    }
    public function store(CustomerRequest $request): JsonResponse
    {
        return $this->ok("succes", Customer::create($request->all()));
    }
    public function update(CustomerRequest $request, Customer $customer): JsonResponse
    {
        $customer->update($request->all());
        return $this->ok("succes", $customer);
    }
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return $this->ok("succes", $customer);
    }
}
