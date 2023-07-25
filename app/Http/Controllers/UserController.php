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
        $data = [
            'title'             => 'Create Users',
            'sub_title'         => 'List',
            'menu_active'       => 'user',
        ];

        return view('pages.user.create', $data);
    }

    public function store()
    {
        // todo
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
