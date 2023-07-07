<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use App\Http\Requests\Browses\Role\CreateRoleRequest;
use App\Http\Requests\Browses\Role\UpdateRoleRequest;

class RoleController extends Controller
{
    const SOURCE = 'core-user';

    public function getRoleList() {
        $data = [
            'title'             => 'Role',
            'sub_title'         => 'List',
            'menu_active'       => 'browse-role',
            'submenu_active'    => 'browse-role'
        ];

        $role = MyHelper::get(self::SOURCE,'v1/role');

        if (isset($role['status']) && $role['status'] == "success") {
            $data['roles'] = $role['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.role.role_list', $data);
    }

    public function createRole(CreateRoleRequest $request) {
        if ($request->status == "1") {
            $status = true;
        } else {
            $status = false;
        }

        $payload = [
            'name'   => $request->name,
            'status' => $status
        ];

        $save = MyHelper::post(self::SOURCE,'v1/role/create' , $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('browse/role')->withSuccess(['New role successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function getRoleDetail($id) {
        $data = [
            'title'             => 'Role',
            'sub_title'         => 'Detail',
            'menu_active'       => 'browse-role',
            'submenu_active'    => 'browse-role'
        ];

        $feature_list = MyHelper::get(self::SOURCE,'v1/feature');
        if (isset($feature_list['status']) && $feature_list['status'] == "success") {
            foreach ($feature_list['data'] as $value) {
                $features[$value['feature_module']][] = [
                    'id' => $value['id'],
                    'feature_type' => $value['feature_type']
                ];
            }
            $data['feature_list'] = $features;
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $role = MyHelper::get(self::SOURCE,'v1/role/detail/'.$id);

        $owned_features = [];
        if (isset($role['status']) && $role['status'] == "success") {
            $data['detail'] = $role['data'];
            foreach ($role['data']['Features'] as $value) {
                $owned_features[] = $value['id'];
            }
            $data['owned_features'] = $owned_features;
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.role.role_detail', $data);
    }

    public function updateRole(UpdateRoleRequest $request){
        if ($request->status == "1") {
            $status = true;
        } else {
            $status = false;
        }

        $feature_ids = [];
        foreach ($request->fature_ids ?? []  as $id) {
            $feature_ids[] = (int)$id;
        }


        $payload = [
            "id" => $request->id,
            "name" => $request->name,
            "status" => $status,
            "feature_ids" => $feature_ids
        ];

        $save = MyHelper::post(self::SOURCE,'v1/role/update' , $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Role successfully updated.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteRole($id){
        $delete= MyHelper::get(self::SOURCE,'v1/role/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return redirect('browse/role')->withSuccess(['Location deleted successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
