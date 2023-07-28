<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Users',
            'sub_title'         => 'List',
            'menu_active'       => 'user',
        ];

        // $cms_user = MyHelper::get('user');
        $cms_user = MyHelper::get('be/user');
        if (isset($cms_user['status']) && $cms_user['status'] == "success") {
            $data['cms_users'] = $cms_user['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.user.index', $data);
    }

    public function create()
    {
        $districts = MyHelper::get('indonesia/districts');

        $outlet = MyHelper::get('be/outlet');

        if (isset($outlet['status']) && $outlet['status'] == "success") {
            $data = [
                'title'             => 'Create Users',
                'sub_title'         => 'List',
                'menu_active'       => 'user',
                'districts'         => $districts['data'],
                'outlets'           => $outlet['result'],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        } 
        return view('pages.user.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // 'equal_id' => 'required|numeric',
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'idc' => 'required',
            'birthdate' => 'required',
            'phone' => 'required|',
            'district' => 'required',
            'admin_role' => 'required',
            'level' => 'required',
            'outlet' => 'required',
            'password' => 'min:6',
            'address' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);

        $payload = [
            // "equal_id"          => $request->equal_id,
            "equal_id" => '1211',
            "name"       => $request->name,
            "username" => $request->username,
            "phone" => $request->phone,
            "email"  => $request->email,
            "idc"  => $request->idc,
            "birthdate"    => $request->birthdate,
            "type"   => $request->admin_role,
            "outlet_id"   => $request->outlet,
            "district_code"    => $request->district,
            "level"    => $request->level,
            "password" => $request->password,
            "address" => $request->address,
            "is_active" => $request->is_active ?? '',

        ];

        $save = MyHelper::post('be/user/', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('user')->withSuccess(['New User successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show()
    {
        // todo
    }

    public function update()
    {
        // todo
    }

    public function deleteUser($id)
    {
        $delete = MyHelper::deleteApi('user/' . $id);

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['User deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
