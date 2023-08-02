<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiOutletController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $outlets = $request->length ? Outlet::paginate($request->length ?? 10) : Outlet::get();
        return $this->ok("succes", $outlets);
    }
    public function show(Outlet $outlet): JsonResponse
    {
        return $this->ok("succes", $outlet);
    }
    public function store(Request $request): JsonResponse
    {
        $outlet = Outlet::create($request->all());
        return $this->ok("succes", $outlet);
    }
    public function update(Request $request, Outlet $outlet): JsonResponse
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
