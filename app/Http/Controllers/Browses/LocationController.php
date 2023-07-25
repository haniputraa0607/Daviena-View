<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Browses\Location\UpdateLocationStatusRequest;
use App\Http\Requests\Browses\Location\CreateLocationRequest;
use App\Http\Requests\Browses\Location\UpdateLocationRequest;
use App\Http\Requests\Browses\Location\CreateLocationDeveloperRequest;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use Exception;

class LocationController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'location';

    public function getLocationList()
    {
        $data = [
            'title'   => 'Location',
            'sub_title'   => 'Location List',
            'menu_active'    => 'browse-location',
            'submenu_active' => 'browse-location'
        ];

        $per_page = request()->query('per_page', 10);
        $page = request()->query('page', 1);
        $location_pagination = MyHelper::get(self::SOURCE, 'v1/location/pagination?per_page=' . $per_page . '&page=' . $page);

        if (isset($location_pagination['status']) && $location_pagination['status'] == "success") {
            $data['locations'] = $location_pagination['data']['data'];
            $data['pagination'] = [
                'total_data' => $location_pagination['data']['total'],
                'per_page' => $location_pagination['data']['per_page'],
                'current_page' => $location_pagination['data']['current_page'],
                'last_page' => $location_pagination['data']['last_page'],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $dev_per_page = request()->query('dev_per_page', 10);
        $dev_page = request()->query('dev_page', 1);
        $developer = MyHelper::get(self::SOURCE, 'v1/location/developer/pagination?per_page=' . $dev_per_page . '&page=' . $dev_page);
        if (isset($developer['status']) && $developer['status'] == "success") {
            $data['developers'] = $developer['data']['data'];
            $data['developer_pagination'] = [
                'total_data' => $developer['data']['total'],
                'per_page' => $developer['data']['per_page'],
                'current_page' => $developer['data']['current_page'],
                'last_page' => $developer['data']['last_page'],
            ];
        } else {
            $data['developers'] = [];
        }

        $location = MyHelper::get(self::SOURCE, 'v1/location');
        if (!isset($location_pagination['status']) || $location_pagination['status'] != "success") {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $existing_image = [];
        foreach ($location['data'] as $value) {
            if (!empty($value['location_image'])) {
                foreach ($value['location_image'] as $image) {
                    array_push($existing_image, $image['path']);
                }
            }
        }
        //Delete image file that deleted images from database
        MyHelper::deleteImageNotExist('location/image', $existing_image);

        return view('browses.location.location_list', $data);
    }

    public function addLocation()
    {
        $data = [
            'title'   => 'Location',
            'sub_title'   => 'New Location',
            'menu_active'    => 'browse-location',
            'submenu_active' => 'browse-location'
        ];

        $developer = MyHelper::get(self::SOURCE, 'v1/location/developer');
        if (isset($developer['status']) && $developer['status'] == "success") {
            $data['developers'] = $developer['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.location.location_new', $data);
    }

    public function createLocation(CreateLocationRequest $request)
    {
        $path = 'image';
        $images = [];
        if (!empty($request->file)) {
            foreach ($request->file as $key => $image) {
                try {
                    $fileName = MyHelper::createFilename($image);

                    $uploadedFile = MyHelper::uploadFile($image, self::ROOTPATH, $path, $fileName);

                    if ($uploadedFile['path'] == null) {
                        return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
                    }
                } catch (Exception $error) {
                    return back()->withErrors($error->getMessage())->withInput();
                }

                $detail_image = [
                    "order_no" => $key,
                    "path" => $uploadedFile['path'],
                ];

                $images[] = $detail_image;
            }
        }

        if ($request->status == "1") {
            $status = true;
        } else {
            $status = false;
        }

        $payload = [
            "status" => $status,
            "name" => $request->name,
            "description" => $request->description,
            "address" => $request->address,
            "time_start" => $request->time_start,
            "time_end" => $request->time_end,
            "latitude" => $request->latitude,
            "longitude" => $request->longitude,
            "building_code" => $request->building_code,
            "location_type" => $request->location_type,
            "developer_id" => $request->developer_id,
            "location_image" => $images
        ];

        $save = MyHelper::post(self::SOURCE, 'v1/location/add', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('browse/location')->withSuccess(['New location successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function getLocationDetail($id)
    {
        $data = [
            'title'   => 'Location',
            'sub_title'   => 'Location Detail',
            'menu_active'    => 'browse-location',
            'submenu_active' => 'browse-location'
        ];
        $error_bag = [];

        $developer = MyHelper::get(self::SOURCE, 'v1/location/developer');
        if (isset($developer['status']) && $developer['status'] == "success") {
            $data['developers'] = $developer['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $detail = MyHelper::get(self::SOURCE, 'v1/location/' . $id);

        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['data'];
            $data['detail']['decrypted_location_id'] = MyHelper::get(self::SOURCE, 'v1/helper/decrypt?id=' . $id)['data'] ?? "";
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $ecs = MyHelper::get(self::SOURCE, 'v1/ecs/location/' . $id);
        if (isset($ecs['status']) && $ecs['status'] == "success") {
            $data['ecs'] = $ecs['data'];
        } else {
            if (isset($ecs['status']) && $ecs['status'] == "error") {
                $error_bag[] = $ecs['message'];
            }
            $data['ecs'] = [];
        }

        $ecs_option = MyHelper::get(self::SOURCE, 'v1/ecs/search');
        if (isset($ecs_option['status']) && $ecs_option['status'] == "success") {
            $data['ecs_option'] = $ecs_option['data'];
        } else {
            if (isset($ecs_option['status']) && $ecs_option['status'] == "error") {
                $error_bag[] = $ecs_option['message'];
            }
            $data['ecs_option'] = [];
        }

        if (count($error_bag) > 0) {
            return view('browses.location.location_detail', $data)->withErrors($error_bag);
        }

        return view('browses.location.location_detail', $data);
    }

    public function updateStatusLocation(UpdateLocationStatusRequest $request)
    {
        if ($request->status == "1") {
            $status = true;
        } else {
            $status = false;
        }

        $payload = [
            'id'        => $request->id,
            'status'    => $status
        ];

        $update = MyHelper::post(self::SOURCE, 'v1/location/update/status', $payload);

        if (isset($update['status']) && $update['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Location status updated successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$update['message']]]);
        }
    }

    public function updateLocation(UpdateLocationRequest $request)
    {
        $path = 'image';
        $location_image = [];
        $new_location_image = [];
        $uploadedFile['path'] = null;
        if (isset($request->image_id) && count($request->image_id) > 0) {
            foreach ($request->image_id as $key => $image_id) {
                try {
                    if (isset($request->file[$key])) {
                        $fileName = MyHelper::createFilename($request->file[$key]);

                        $uploadedFile = MyHelper::uploadFile($request->file[$key], self::ROOTPATH, $path, $fileName);

                        if ($uploadedFile['path'] == null) {
                            return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
                        }
                    }
                } catch (Exception $error) {
                    return back()->withErrors($error->getMessage())->withInput();
                }

                $detail_image = [
                    "id"        => $image_id,
                    "order_no"  => $key,
                    "path"      => $uploadedFile['path']
                ];

                if ($image_id == null) {
                    $new_location_image[] = $detail_image;
                } else {
                    $location_image[] = $detail_image;
                }
                $uploadedFile['path'] = null;
            }
        }

        if ($request->status == "1") {
            $status = true;
        } else {
            $status = false;
        }

        $payload = [
            "id"                    => $request->id,
            "status"                => $status,
            "name"                  => $request->name,
            "building_code"         => $request->building_code,
            "location_type"         => $request->location_type,
            "developer_id"          => $request->developer_id,
            "description"           => $request->description,
            "address"               => $request->address,
            "time_start"            => $request->time_start,
            "time_end"              => $request->time_end,
            "latitude"              => $request->latitude,
            "longitude"             => $request->longitude,
            "location_image"        => $location_image,
            "new_location_image"    => $new_location_image
        ];
        $save = MyHelper::post(self::SOURCE, 'v1/location/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('browse/location/' . $request->id)->withSuccess(['Location has been updated.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteLocation($id)
    {
        $delete = MyHelper::get(self::SOURCE, 'v1/location/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return redirect('browse/location')->withSuccess(['Location deleted successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function searchLocation()
    {
        $search = request()->query("search");

        $url = 'v1/location/search';
        if (!empty($search)) {
            $url = 'v1/location/search?search=' . $search;
        }

        $location = MyHelper::get(self::SOURCE, $url);

        if (isset($location['status']) && $location['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['search location success'], 'data' => $location['data']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$location['message']]]);
        }
    }

    public function deleteDevice($location_id, $device_id)
    {
        return $location_id . " - " . $device_id;
    }

    public function createLocationDeveloper(CreateLocationDeveloperRequest $request)
    {
        $payload = [
            "name" => $request->name,
            "developer_code" => $request->code
        ];

        $save = MyHelper::post(self::SOURCE, 'v1/location/developer/add', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('browse/location')->withSuccess(['New location successfully added.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteLocationDeveloper($id)
    {
        $delete = MyHelper::get(self::SOURCE, 'v1/location/developer/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Developer deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
