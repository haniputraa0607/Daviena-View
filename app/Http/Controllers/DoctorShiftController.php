<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorShiftRequest;
use App\Http\Requests\UpdateDoctorShiftRequest;
use App\Lib\MyHelper;
use App\Models\DoctorShift;
use Illuminate\Http\Request;

class DoctorShiftController extends Controller
{
    private string $path = 'be/doctor-shift/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Doctor Shift',
            'sub_title'         => 'List',
            'menu_active'       => 'doctor-shift',
        ];
        return view('pages.doctor-shift.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Doctor Shift',
            'sub_title'         => 'List',
            'menu_active'       => 'doctor-shift',
        ];

        return view('pages.doctor-shift.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        dd($payload);
        $save = MyHelper::post($this->path, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('doctor-shift')->withSuccess(['New Doctor Shift successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Doctor Shift',
            'sub_title'         => 'Detail',
            'menu_active'       => 'doctor-shift',
        ];
        $doctorShift = MyHelper::get($this->path . $id);
        if (isset($doctorShift['status']) && $doctorShift['status'] == "success") {
            $data['detail'] = $doctorShift['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.doctor-shift.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('doctor-shift')->withSuccess(['CMS Doctor Shift detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteDoctorShift($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Doctor Shift deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
