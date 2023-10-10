<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorScheduleRequest;
use App\Http\Requests\UpdateDoctorScheduleRequest;
use App\Lib\MyHelper;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    private string $path = 'be/doctor-schedule/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Doctor Schedule',
            'sub_title'         => 'List',
            'menu_active'       => 'doctor-schedule',
        ];
        return view('pages.doctor-schedule.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Doctor Schedule',
            'sub_title'         => 'List',
            'menu_active'       => 'doctor-schedule',
        ];
        return view('pages.doctor-schedule.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('doctor-schedule')->withSuccess(['New Doctor Schedule successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Doctor Schedule',
            'sub_title'         => 'Detail',
            'menu_active'       => 'doctor-schedule',
        ];
        $save = MyHelper::get($this->path . $id);
        if (isset($save['status']) && $save['status'] == "success") {
            $data['detail'] = $save['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.doctor-schedule.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('doctor-schedule')->withSuccess(['CMS Doctor Schedule detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteDoctorSchedule($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Doctor Schedule deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
