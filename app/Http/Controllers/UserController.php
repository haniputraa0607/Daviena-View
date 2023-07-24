<?php

namespace App\Http\Controllers;
use App\Lib\MyHelper;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Users',
            'sub_title'         => 'List',
            'menu_active'       => 'user',
        ];

        $cms_user = MyHelper::get('user');
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
        dd("indeex");
    }

    public function show()
    {
        dd("indeex");
    }

    public function update()
    {
        dd("indeex");
    }

    public function delete()
    {
        dd("indeex");
    }
}
