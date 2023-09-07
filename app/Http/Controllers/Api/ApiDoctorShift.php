<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorShift\Request as DoctorShiftRequest;
use App\Lib\MyHelper;
use App\Models\DoctorShift;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiDoctorShift extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DoctorShift::query();
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('time', function ($row) {
                return Carbon::parse($row->start)->format('H:i') . ' - ' . Carbon::parse($row->end)->format('H:i');
            })
            ->editColumn('name', function ($row) {
                return $row->day . ' - ' . $row->name;
            })
            ->editColumn('price', function ($row) {
                return MyHelper::rupiah($row->price);
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('doctor-shift.detail', ['id' => $row->id]) . '">
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
        return $this->ok("succes get Doctor Shift", DoctorShift::select('name', 'id')->get());
    }
    public function show(DoctorShift $doctorShift): JsonResponse
    {
        $doctorShift->user;
        return $this->ok("succes", $doctorShift);
    }
    public function store(DoctorShiftRequest $request): JsonResponse
    {
        $doctorShift = DoctorShift::create($request->all());
        return $this->ok("succes", $doctorShift);
    }
    public function update(DoctorShiftRequest $request, DoctorShift $doctorShift): JsonResponse
    {
        $doctorShift->update($request->all());
        return $this->ok("succes", $doctorShift);
    }
    public function destroy(DoctorShift $doctorShift): JsonResponse
    {
        $doctorShift->delete();
        return $this->ok("succes", $doctorShift);
    }
}
