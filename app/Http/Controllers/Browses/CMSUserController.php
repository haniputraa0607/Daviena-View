<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use App\Http\Requests\Browses\CMSUser\UpdateDetailUserRequest;
use App\Http\Requests\Browses\CMSUser\UpdatePasswordUserRequest;
use App\Http\Requests\Browses\CMSUser\UpdateRoleUserRequest;
use App\Http\Requests\Browses\CMSUser\CreateUserRequest;

class CMSUserController extends Controller
{
    const SOURCE = 'core-user';

    public function getUserList() {
        $data = [
            'title'             => 'CMS Users',
            'sub_title'         => 'List',
            'menu_active'       => 'browse-cms-user',
            'submenu_active'    => 'browse-cms-user'
        ];

        $cms_user = MyHelper::get(self::SOURCE,'v1/user');
        if (isset($cms_user['status']) && $cms_user['status'] == "success") {
            $data['cms_users'] = $cms_user['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.cms-user.cms_user_list', $data);
    }

    public function getUserDetail($id) {
        $data = [
            'title'             => 'CMS Users',
            'sub_title'         => 'Detail',
            'menu_active'       => 'browse-cms-user',
            'submenu_active'    => 'browse-cms-user'
        ];

        $role = MyHelper::get(self::SOURCE,'v1/role?status=true');
        if (isset($role['status']) && $role['status'] == "success") {
            $data['roles'] = $role['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $cms_user = MyHelper::get(self::SOURCE,'v1/user/detail/' . $id);
        if (isset($cms_user['status']) && $cms_user['status'] == "success") {
            $data['detail'] = $cms_user['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.cms-user.cms_user_detail', $data);
    }

    public function addUser() {
        $data = [
            'title'             => 'CMS Users',
            'sub_title'         => 'New',
            'menu_active'       => 'browse-cms-user',
            'submenu_active'    => 'browse-cms-user'
        ];

        $role = MyHelper::get(self::SOURCE,'v1/role?status=true');
        if (isset($role['status']) && $role['status'] == "success") {
            $data['roles'] = $role['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.cms-user.cms_user_add', $data);
    }

    public function createUser(CreateUserRequest $request) {
        if (isset($request->role)) {
            $role = $request->role;
            $admin_role = '';
        } else {
            $role = "admin";
            $admin_role = $request->admin_role;
        }

        $payload = [
            "name"      => $request->name,
            "email"     => $request->email,
            "password"  => $request->password,
            "role"      => $role,
            "role_id"   => $admin_role,
        ];

        $save = MyHelper::post(self::SOURCE,'v1/user/create', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('browse/cms-user')->withSuccess(['New User successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }


    public function updateRoleUser(UpdateRoleUserRequest $request) {
        $payload = [
            'id'                    => $request->id,
            'role'                  => $request->role,
            'super_admin_password'  => $request->super_admin_password
        ];

        $update = MyHelper::post(self::SOURCE,'v1/user/update/role', $payload);

        if (isset($update['status']) && $update['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['CMS user role updated successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$update['message']]]);
        }
    }

    public function updateUserDetail(UpdateDetailUserRequest $request) {
        if ($request->is_active == '1') {
            $is_active = true;
        } else {
            $is_active = false;
        }

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

    public function updatePasswordDetail(UpdatePasswordUserRequest $request) {
        $payload = [
            'id'                    => $request->id,
            'super_admin_password'  => $request->super_admin_password,
            'new_password'          => $request->new_password
        ];

        $save = MyHelper::post(self::SOURCE,'v1/user/update/password', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withFragment('#tab_password')->withSuccess(['CMS User password has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withFragment('#tab_password')->withErrors($save['message'])->withInput();
            }
            return back()->withFragment('#tab_password')->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
