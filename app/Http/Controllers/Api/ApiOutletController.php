<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Outlet\Create;
use App\Http\Requests\Outlet\Update;
use App\Models\Outlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiOutletController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Outlet::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('city', function ($row) {
                return $row->district->name;
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('outlet.detail', ['id' => $row->id]) . '">
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
        return $this->ok("succes get outlet", Outlet::select('name', 'id')->get());
    }
    public function show(Outlet $outlet): JsonResponse
    {
        return $this->ok("succes", $outlet);
    }
    public function store(Create $request): JsonResponse
    {
        $outlet = Outlet::create($request->all());
        return $this->ok("succes", $outlet);
    }
    public function update(Update $request, Outlet $outlet): JsonResponse
    {
        $outlet->update($request->all());
        return $this->ok("succes", $outlet);
    }
    public function destroy(Outlet $outlet): JsonResponse
    {
        $outlet->delete();
        return $this->ok("succes", $outlet);
    }
}
