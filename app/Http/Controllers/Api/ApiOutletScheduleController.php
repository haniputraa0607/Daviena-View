<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutletSchedule\Request as OutletScheduleRequest;
use App\Models\OutletSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiOutletScheduleController extends Controller
{
    public function update(OutletScheduleRequest $request): JsonResponse
    {
        $outletSchedule = OutletSchedule::find($request->id);
        // return $this->ok("succes",$outletSchedule);
        // return $this->ok("succes",$request->all());
        $outletSchedule->update($request->all());
        return $this->ok("succes", ['request' => $request->all(), 'result' => $outletSchedule]);
    }
}
