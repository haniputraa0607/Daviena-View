<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiPartnerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $partner = $request->length ?  Partner::paginate($request->length ?? 10) : Partner::get();
        return $this->ok("Success Get Data All partner", $partner);
    }

    public function show(Partner $partner): JsonResponse
    {
        return $this->ok("Success", $partner);
    }

    public function store(Request $request): JsonResponse
    {
        $partner = Partner::create($request->all());
        return $this->ok("Success", $partner);
    }
    public function update(Request $request, Partner $partner): JsonResponse
    {
        $partner->update($request->all());
        return $this->ok("Success", $partner);
    }
    public function destroy(Partner $partner): JsonResponse
    {
        $partner->delete();
        return $this->ok("Success", $partner);
    }
}
