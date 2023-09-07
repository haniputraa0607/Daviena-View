<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorScheduleDateRequest;
use App\Http\Requests\UpdateDoctorScheduleDateRequest;
use App\Lib\MyHelper;
use App\Models\DoctorScheduleDate;
use Illuminate\Http\Request;

class DoctorScheduleDateController extends Controller
{
    private string $path = 'be/doctor-schedule-date/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Doctor Schedule Date',
            'sub_title'         => 'List',
            'menu_active'       => 'doctor-schedule-date',
        ];
        return view('pages.doctor-schedule-date.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Doctor Schedule Date',
            'sub_title'         => 'List',
            'menu_active'       => 'doctor-schedule-date',
        ];

        return view('pages.doctor-schedule-date.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);
        dd($payload, $save);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('doctor-schedule-date')->withSuccess(['New Doctor Schedule Date successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Doctor Schedule Date',
            'sub_title'         => 'Detail',
            'menu_active'       => 'doctor-schedule-date',
        ];
        $save = MyHelper::get($this->path . $id);
        if (isset($save['status']) && $save['status'] == "success") {
            $data['detail'] = $save['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.doctor-schedule-date.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('doctor-schedule-date')->withSuccess(['CMS Doctor Schedule Date detail has been updated.']);
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
            return response()->json(['status' => 'success', 'messages' => ['Doctor Schedule Date deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
