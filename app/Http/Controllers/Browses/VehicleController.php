<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Browses\Vehicle\CreateVehicleBrandRequest;
use App\Http\Requests\Browses\Vehicle\CreateVehicleTypeRequest;
use App\Http\Requests\Browses\Vehicle\UpdateVehicleBrandVisibilityRequest;
use App\Http\Requests\Browses\Vehicle\UpdateVehicleTypeVisibilityRequest;
use App\Http\Requests\Browses\Vehicle\UpdateVehicleBrandRequest;
use App\Http\Requests\Browses\Vehicle\UpdateVehicleTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\MyHelper;

class VehicleController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'vehicle';

    public function getVehicleBrand() {
        $data = [
            'title'   => 'Vehicle',
            'sub_title'   => 'Vehicle Brand List',
            'menu_active'    => 'browse-vehicle',
            'submenu_active' => 'browse-vehicle'
        ];

        $count_brand = MyHelper::get(self::SOURCE,'v1/vehicle/total');
        if (isset($count_brand['status']) && $count_brand['status'] == "success") {
            $data['count_brand'] = $count_brand['data'];
        } else {
            $data['count_brand'] = 0;
        }
        $count_type = MyHelper::get(self::SOURCE,'v1/vehicle/type/total');
        if (isset($count_type['status']) && $count_type['status'] == "success") {
            $data['count_type'] = $count_type['data'];
        } else {
            $data['count_type'] = 0;
        }

        $search = request()->query('search', "");
        $per_page = request()->query('per_page', 10);
        $page = request()->query('page', 1);
        $vehicle = MyHelper::get(self::SOURCE,'v1/vehicle/pagination?per_page=' . $per_page . '&page=' . $page . '&search=' . $search);


        $all_vehicle = MyHelper::get(self::SOURCE, 'v1/vehicle');
        $existing_image = [];
        foreach ($all_vehicle['data'] as $value) {
                array_push($existing_image, $value['logo']);
        }

        //Delete image file that deleted images from database
        MyHelper::deleteImageNotExist('vehicle/logo', $existing_image);

        if (isset($vehicle['status']) && $vehicle['status'] == "success") {
            $data['vehicle_brand'] = $vehicle['data']['data'];
            $data['pagination'] = [
                'total_data' => $vehicle['data']['total'],
                'per_page' => $vehicle['data']['per_page'],
                'current_page' => $vehicle['data']['current_page'],
                'last_page' => $vehicle['data']['last_page'],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.vehicle.vehicle_brand', $data);
    }

    public function getDetailVehicleBrand($id) {
        $data = [
            'title'   => 'Vehicle',
            'sub_title'   => 'Vehicle Brand Detail',
            'menu_active'    => 'browse-vehicle',
            'submenu_active' => 'browse-vehicle'
        ];

        //initiate variable
        $all_user_ids = [];
        $filtered_user = [];
        $user_ids = [];
        $user_data = [];
        $user_vehicle_data = [];
        $data['pagination'] = [
            'total_data' => null,
            'per_page' => null,
            'current_page' => null,
            'last_page' => null,
        ];

        //Get All user vehicle with filter
        $get_filter_type = request()->query('type', "");
        if ($get_filter_type != "") {
            $filter_type = "&vehicle_type_id=" . $get_filter_type;
        } else {
            $filter_type = "";
        }

        $all_user_vehicle = MyHelper::get(self::SOURCE,'v1/user-vehicle?vehicle_brand_id=' . $id . $filter_type);
        if (isset($all_user_vehicle['status']) && $all_user_vehicle['status'] == "success") {
            foreach ($all_user_vehicle['data'] as $user) {
                $all_user_ids[] = $user['user_id'];
            }
        }
        $all_string_ids = implode(",",$all_user_ids);

        //Get All user detail with filter based on all user filtered
        $filter_status = request()->query('status', "");
        $filter_search = request()->query('user', "");
        $all_user_list = MyHelper::get('core-user','v1/mobile-user/list?user_ids=' . $all_string_ids . '&status=' . $filter_status . '&search=' . $filter_search);
        if (isset($all_user_list['status']) && $all_user_list['status'] == "success") {
            foreach ($all_user_list['data'] as $user) {
                $filtered_user[] = $user['id'];
            }
        }
        $all_string_user_ids = implode(",",$filtered_user);

        if ($all_string_user_ids != "") {
            $user_filtered = "&user_ids=".$all_string_user_ids;

            //Get Pagination user vehicle with filtered user
            $per_page = request()->query('per_page', 10);
            $page = request()->query('page', 1);
            $user_vehicle = MyHelper::get(self::SOURCE,'v1/user-vehicle/pagination?vehicle_brand_id=' . $id . '&per_page=' . $per_page . '&page=' . $page . $filter_type . $user_filtered);
            if (isset($user_vehicle['status']) && $user_vehicle['status'] == "success") {
                foreach ($user_vehicle['data']['data'] as $user) {
                    $user_ids[] = $user['user_id'];
                }
                $data['pagination'] = [
                    'total_data' => $user_vehicle['data']['total'],
                    'per_page' => $user_vehicle['data']['per_page'],
                    'current_page' => $user_vehicle['data']['current_page'],
                    'last_page' => $user_vehicle['data']['last_page'],
                ];
            }
            $string_ids = implode(",",$user_ids);

            //Get user detail by pagination vehicle
            $user_list = MyHelper::get('core-user','v1/mobile-user/list?user_ids=' . $string_ids);
            if (isset($user_list['status']) && $user_list['status'] == "success") {
                foreach ($user_list['data'] as $user) {
                    $user_data[$user['id']] = $user;
                }
            }

            foreach ($user_vehicle['data']['data'] as $vehicle) {
                if (isset($user_data[$vehicle['user_id']])) {
                    $user_vehicle_data[] = [
                        'name' => $user_data[$vehicle['user_id']]['name'],
                        'email' => $user_data[$vehicle['user_id']]['email'],
                        'vehicle_type' => $vehicle['VehicleType']['name'],
                        'status' => $user_data[$vehicle['user_id']]['is_deleted'] ? "deleted" : (!$user_data[$vehicle['user_id']]['is_active'] ? "non-active" : "active")
                    ];
                }
            }

            $data['user_vehicle'] = $user_vehicle_data;
        }

        $detail = MyHelper::get(self::SOURCE,'v1/vehicle/' . $id);

        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.vehicle.vehicle_brand_detail', $data);
    }

    public function updateDetailVehicleBrand(UpdateVehicleBrandRequest $request, $id) {
        $path = 'logo';
        $logo_path = "";
        if (!empty($request->logo_brand)) {
            $fileName = MyHelper::createFilename($request->logo_brand);

            $uploadedFile = MyHelper::uploadFile($request->logo_brand, self::ROOTPATH, $path, $fileName);

            if ($uploadedFile['path'] == null) {
                return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
            }

            $logo_path = $uploadedFile['path'];
        }

        if ($request->visibility == "1") {
            $visibility = true;
        } else {
            $visibility = false;
        }

        $payload = [
            'id'            => $request->id,
            'name'          => $request->name,
            'logo'          => $logo_path,
            'visibility'    => $visibility,
        ];

        $save = MyHelper::post(self::SOURCE,'v1/vehicle/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Vehicle brand updated successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

    }

    public function createVehicleBrand(CreateVehicleBrandRequest $request) {
        $path = 'logo';
        $logo_path = "";
        if (!empty($request->logo_brand)) {
            $fileName = MyHelper::createFilename($request->logo_brand);

            $uploadedFile = MyHelper::uploadFile($request->logo_brand, self::ROOTPATH, $path, $fileName);

            if ($uploadedFile['path'] == null) {
                return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
            }

            $logo_path = $uploadedFile['path'];
        }

        if ($request->visibility == "1") {
            $visibility = true;
        } else {
            $visibility = false;
        }

        $payload = [
            'name'          => $request->name,
            'logo'          => $logo_path,
            'visibility'    => $visibility,
        ];

        $save = MyHelper::post(self::SOURCE,'v1/vehicle/add' , $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('browse/vehicle')->withSuccess(['New vehicle brand successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function createVehicleType(CreateVehicleTypeRequest $request) {
        if ($request->type_visibility == "1") {
            $visibility = true;
        } else {
            $visibility = false;
        }
        $payload = [
            'brand_id'      => $request->id,
            'name'          => $request->type_name,
            "ac_max"        => (float) $request->ac_max,
            "dc_max"        => (float) $request->dc_max,
            'visibility'    => $visibility,
        ];

        $save = MyHelper::post(self::SOURCE,'v1/vehicle/type/add' , $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['New vehicle type successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteVehicleBrand($id) {
        $delete= MyHelper::get(self::SOURCE,'v1/vehicle/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Vehicle Brand deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }

    public function deleteVehicleType($id) {
        $delete= MyHelper::get(self::SOURCE,'v1/vehicle/type/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Vehicle Type deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }

    public function updateVisibilityVehicleBrand(UpdateVehicleBrandVisibilityRequest $request) {
        if ($request->visibility == "1") {
            $visibility = true;
        } else {
            $visibility = false;
        }

        $payload = [
            'id'            => $request->id,
            'visibility'    => $visibility
        ];

        $update= MyHelper::post(self::SOURCE,'v1/vehicle/update/visibility', $payload);

        if (isset($update['status']) && $update['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Vehicle Brand visibility updated successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$update['message']]]);
        }
    }

    public function updateVisibilityVehicleType(UpdateVehicleTypeVisibilityRequest $request) {
        if ($request->visibility == "1") {
            $visibility = true;
        } else {
            $visibility = false;
        }

        $payload = [
            'id'            => $request->id,
            'visibility'    => $visibility
        ];

        $update= MyHelper::post(self::SOURCE,'v1/vehicle/type/update/visibility', $payload);

        if (isset($update['status']) && $update['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Vehicle Brand visibility updated successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$update['message']]]);
        }
    }

    public function updateVehicleType(UpdateVehicleTypeRequest $request){
        $payload = [
            "id" => $request->id,
            "name" => $request->name,
            "ac_max" => (float) $request->ac_max,
            "dc_max" => (float) $request->dc_max
        ];

        $save = MyHelper::post(self::SOURCE,'v1/vehicle/type/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Vehicle Type updated successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
