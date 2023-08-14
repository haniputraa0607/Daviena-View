<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private string $path = 'be/user/';
    public function index()
    {
        $data = [
            'title'             => 'Manage Users',
            'sub_title'         => 'List',
            'menu_active'       => 'user',
        ];

        return view('pages.user.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Manage Users',
            'sub_title'         => 'List',
            'menu_active'       => 'user',
        ];
        return view('pages.user.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('user')->withSuccess(['New User successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $districts = MyHelper::get('indonesia/districts');

        $outlets = MyHelper::get('be/outlet/name-id');

        $detail = MyHelper::get($this->path . $id);

        if (isset($outlets['status']) && $outlets['status'] == "success" && isset($detail['status'])  && $detail['status'] == 'success') {
            $data = [
                'title'             => 'Create Users',
                'sub_title'         => 'List',
                'menu_active'       => 'user',
                'districts'         => $districts['data'],
                'outlets'           => $outlets['result'],
                'detail'            => $detail['result']
            ];
        } else {
            return back()->withErrors($detail['error'] ?? "Something went wrong")->withInput();
        }

        return view('pages.user.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('user')->withSuccess(['CMS User detail has been updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteUser($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['User deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
