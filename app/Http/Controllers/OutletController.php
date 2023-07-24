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

        $outlet = MyHelper::get('outlet');
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
            "outlet_phone"  => $request->phone,
            "outlet_email"  => $request->email,
            "id_partner"    => $request->partner,
            "outlet_code"   => $request->outlet_code,
            "activities"    => array('product', 'consultation')
        ];

        $save = MyHelper::post('outlet', $payload);

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

        $outlet = MyHelper::get('outlet/' . $id);
        if (isset($outlet['status']) && $outlet['status'] == "success") {
            $data['detail'] = $outlet['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.outlet.detail', $data);
    }

    public function update(Request $request)
    {
        $payload = [
            'id'                    => $request->id,
            'name'                  => $request->name,
            'email'                 => $request->email,
            'is_active'             => $is_active,
            'role_id'               => $request->admin_role,
            'super_admin_password'  => $request->super_admin_password
        ];

        $save = MyHelper::post(self::SOURCE,'v1/user/update/detail', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withFragment('#tab_detail')->withSuccess(['CMS User detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withFragment('#tab_detail')->withErrors($save['message'])->withInput();
            }
            return back()->withFragment('#tab_detail')->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function delete($id)
    {
        $delete= MyHelper::deleteApi('outlet/' . $id);

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Outlet deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
