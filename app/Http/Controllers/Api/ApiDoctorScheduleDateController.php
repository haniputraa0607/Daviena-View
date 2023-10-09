<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorScheduleDate\Request as DoctorScheduleDateRequest;
use App\Models\DoctorScheduleDate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiDoctorScheduleDateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DoctorScheduleDate::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('doctorSchedule', function ($row) {
                return $row->doctorSchedule?->schedule_month . ' - ' . $row->doctorSchedule?->schedule_year;
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('doctor-schedule-date.detail', ['id' => $row->id]) . '">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function nameId(Request $request): JsonResponse
    {
        return $this->ok("succes get DoctorShift", DoctorScheduleDate::select('name', 'id')->get());
    }
    public function show(DoctorScheduleDate $doctorScheduleDate): JsonResponse
    {
        $doctorScheduleDate->DoctorSchedule;
        return $this->ok("succes", $doctorScheduleDate);
    }
    public function store(DoctorScheduleDateRequest $request): JsonResponse
    {
        return $this->ok("succes", $request->all());
        $doctorScheduleDate = DoctorScheduleDate::create($request->all());
        return $this->ok("succes", $doctorScheduleDate);
    }
    public function update(DoctorScheduleDateRequest $request, DoctorScheduleDate $doctorScheduleDate): JsonResponse
    {
        $doctorScheduleDate->update($request->all());
        return $this->ok("succes", $doctorScheduleDate);
    }
    public function destroy(DoctorScheduleDate $doctorScheduleDate): JsonResponse
    {
        $doctorScheduleDate->delete();
        return $this->ok("succes", $doctorScheduleDate);
    }
}
