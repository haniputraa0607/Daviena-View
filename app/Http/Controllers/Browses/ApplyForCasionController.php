<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Browses\ApplyForCasion\UpdateApplyCasionRequest;
use App\Http\Requests\Browses\ApplyForCasion\UpdateStatusApplyCasionRequest;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use App\Exports\ApplyForCasionExport;
use Maatwebsite\Excel\Facades\Excel;

class ApplyForCasionController extends Controller
{
    const SOURCE = 'core-api';

    public function getApplyCasionList()
    {
        $data = [
            'title'   => 'Apply For Casion',
            'sub_title'   => 'Apply For Casion List',
            'menu_active'    => 'browse-apply-casion',
            'submenu_active' => 'browse-apply-casion'
        ];

        $applyCasion = MyHelper::get(self::SOURCE, 'v1/apply-for-casion');

        if (isset($applyCasion['status']) && $applyCasion['status'] == "success") {
            $data['apply_casion'] = $applyCasion['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.apply-for-casion.apply_casion_list', $data);
    }

    public function getApplyCasionDetail($id)
    {
        $data = [
            'title'   => 'Apply For Casion',
            'sub_title'   => 'Apply For Casion Detail',
            'menu_active'    => 'browse-apply-casion',
            'submenu_active' => 'browse-apply-casion'
        ];

        $detail = MyHelper::get(self::SOURCE, 'v1/apply-for-casion/' . $id);

        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.apply-for-casion.apply_casion_detail', $data);
    }

    public function updateDetailApplyCasion(UpdateApplyCasionRequest $request)
    {
        $payload = $request->except('_token');

        $save = MyHelper::post(self::SOURCE, 'v1/apply-for-casion/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Apply for Casion detail updated successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function updateStatusApplyCasion(UpdateStatusApplyCasionRequest $request)
    {
        $payload = [
            'id'        => $request->id,
            'status'    => $request->status
        ];

        $update = MyHelper::post(self::SOURCE, 'v1/apply-for-casion/update/status', $payload);

        if (isset($update['status']) && $update['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Apply for Casion status updated successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$update['message']]]);
        }
    }

    public function exportApplyCasion()
    {
        $applyCasion = MyHelper::get(self::SOURCE, 'v1/apply-for-casion');
        $data = [];
        foreach ($applyCasion['data'] as $apply) {
            $data[] = [
                'name' => $apply['name'],
                'email' => $apply['email'],
                'phone' => $apply['phone_number'],
                'location' => $apply['location_name'],
                'address' => $apply['location_address'],
                'relation' => $apply['relation_to_location'],
                'status' => $apply['status'],
                'time' => date('Y-m-d H:i:s', strtotime($apply['time_submitted'])),
            ];
        }

        return Excel::download(new ApplyForCasionExport($data), 'apply_for_casion_' . md5(time()) . '.csv');
    }
}
