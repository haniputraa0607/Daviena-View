<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiGrievanceControlller extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // $data = Grievance::paginate($request->length ?? 10);
        $data = Grievance::get();
        return $this->ok("success", $data);
    }

    public function store(Request $request): JsonResponse
    {
        $grievance = Grievance::create($request->all());
        return $this->ok("success", $grievance);
    }

    public function show(Grievance $grievance): JsonResponse
    {
        return $this->ok("success", $grievance);
    }

    public function update(Request $request, Grievance $grievance): JsonResponse
    {
        $grievance->update($request->all());
        return $this->ok("success", $grievance);
    }

    public function destroy(Grievance $grievance): JsonResponse
    {
        $grievance->delete();
        return $this->ok("success", $grievance);
    }
}
