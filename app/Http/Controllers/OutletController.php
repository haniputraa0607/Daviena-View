<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Outlet',
            'sub_title'         => 'List',
            'menu_active'       => 'outlet',
        ];

        $outlet = MyHelper::get('be/outlet');
        if (isset($outlet['status']) && $outlet['status'] == "success") {
            $data['outlets'] = $outlet['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.outlet.index', $data);
    }

    public function create()
    {
        $districts = MyHelper::get('indonesia/districts');
        $data = [
            'title'             => 'Create Outlet',
            'sub_title'         => 'List',
            'menu_active'       => 'outlet',
            'districts'         => $districts['data'],
        ];

        return view('pages.outlet.create', $data);
    }

    public function store(Request $request)
    {

        $payload = [
            "name"          => $request->name,
            "address"       => $request->address,
            "district_code" => $request->district,
            "coordinates" => [
                "latitude" => $request->latitude,
                "longitude" => $request->longitude
            ],
            "outlet_phone"  => $request->phone,
            "outlet_email"  => $request->email,
            "status"  => $request->status ?? 'Inactive',
            "id_partner"    => $request->partner,
            "outlet_code"   => $request->outlet_code,
            "activities"    => $request->activities
        ];

        $save = MyHelper::post('be/outlet', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('outlet')->withSuccess(['New Outlet successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $districts = MyHelper::get('indonesia/districts');
        $data = [
            'title'             => 'CMS Detail Outlet',
            'sub_title'         => 'Detail',
            'districts'         => $districts['data'],
        ];

        $outlet = MyHelper::get('be/outlet/' . $id);
        if (isset($outlet['status']) && $outlet['status'] == "success") {
            $data['detail'] = $outlet['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.outlet.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = [
            "name"          => $request->name,
            "address"       => $request->address,
            "district_code" => $request->district,
            "coordinates" => [
                "latitude" => $request->latitude,
                "longitude" => $request->longitude
            ],
            "outlet_phone"  => $request->phone,
            "status"  => $request->status ?? 'Inactive',
            "outlet_email"  => $request->email,
            "id_partner"    => $request->partner,
            "outlet_code"   => $request->outlet_code,
            "activities"    => $request->activities
        ];

        $save = MyHelper::patch('outlet/' . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('outlet')->withSuccess(['CMS Outlet detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withFragment('#tab_detail')->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteOutlet($id)
    {
        $delete = MyHelper::deleteApi('be/outlet/' . $id);

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Outlet deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
