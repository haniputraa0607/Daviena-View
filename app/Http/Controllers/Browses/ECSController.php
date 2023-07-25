<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\Browses\ECS\AssignECSLocationRequest;

class ECSController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'device';

    public function getECSList()
    {
        $data = [
            'title'   => 'ECS',
            'sub_title'   => 'Electronic Charging Station',
            'menu_active'    => 'browse-ecs',
            'submenu_active' => 'browse-ecs'
        ];
        $error_bag = [];
        if (!extension_loaded('imagick')) {
            $error_bag[] = 'ImageMagick extension is not installed or not enabled';
        }

        $location_ids = [];
        $device = MyHelper::get(self::SOURCE, 'v1/ecs');
        if (isset($device['status']) && $device['status'] == "success") {
            foreach ($device['data'] as $location) {
                $location_ids[] = $location['location_id'];
            }
        } else {
            if (isset($device['status']) && $device['status'] == "error") {
                $error_bag[] = $device['message'];
            }
            $data['devices'] = [];
        }
        $string_location_ids = implode(",", $location_ids);

        $location_list = MyHelper::get(self::SOURCE, 'v1/location/list?location_ids=' . $string_location_ids);
        $location_data = [];
        if (isset($location_list['status']) && $location_list['status'] == "success") {
            if (count($location_list['data']) > 0) {
                foreach ($location_list['data'] as $location) {
                    $location_data[$location['id']] = $location;
                }
            }
        }

        foreach ($device['data'] as $device) {
            $location_name = $device["location_id"] != 0 ? $location_data[$device["location_id"]]["name"] ?? "-" : "-";
            $data['devices'][] = [
                "id" => $device["id"],
                "ecs_id" => $device["ecs_id"],
                "status" => $device["status"],
                "num_of_connector" => $device["num_of_connector"] == 0 ? "-" : $device["num_of_connector"],
                "location_name" => $location_name,
            ];
        }

        if (count($error_bag) > 0) {
            return view('browses.ecs.ecs_list', $data)->withErrors($error_bag);
        }
        return view('browses.ecs.ecs_list', $data);
    }

    public function getEcsDetail($id)
    {
        $data = [
            'title'   => 'ECS',
            'sub_title'   => 'Detail Electronic Charging Station',
            'menu_active'    => 'browse-ecs',
            'submenu_active' => 'browse-ecs'
        ];

        $error_bag = [];
        $device = MyHelper::get(self::SOURCE, 'v1/ecs/' . $id);
        if (isset($device['status']) && $device['status'] == "success") {
            $data['device_detail'] = $device['data'];
            if ($device['data']['status'] == 'NEW') {
                $location_option = MyHelper::get(self::SOURCE, 'v1/location/search');
                if (isset($location_option['status']) && $location_option['status'] == "success") {
                    $data['location_option'] = $location_option['data'];
                } else {
                    if (isset($location_option['status']) && $location_option['status'] == "error") {
                        $error_bag[] = $location_option['message'];
                    }
                    $data['location_option'] = [];
                }
            }
        } else {
            if (isset($device['status']) && $device['status'] == "error") {
                $error_bag[] = $device['message'];
            }
            $data['device_detail'] = [];
        }

        $location = MyHelper::get(self::SOURCE, 'v1/location/list?location_ids=' . $device['data']['location_id']);
        if (isset($location['status']) && $location['status'] == "success") {
            foreach ($location['data'] as $location) {
                $data['location_detail'] = $location;
            }
        } else {
            if (isset($location['status']) && $location['status'] == "error") {
                $error_bag[] = $location['message'];
            }
            $data['location_detail'] = [];
        }

        $connectors = MyHelper::get(self::SOURCE, 'v1/ecs/connector/' . $id);
        if (isset($connectors['status']) && $connectors['status'] == "success") {
            $data['connector_list'] = $connectors['data'];
        } else {
            if (isset($connectors['status']) && $connectors['status'] == "error") {
                $error_bag[] = $connectors['message'];
            }
            $data['connector_list'] = [];
        }

        $custom_price = MyHelper::get('core-transaction', 'v1/price-setting/custom-price/option');
        if (isset($custom_price['status']) && $custom_price['status'] == "success") {
            $data['custom_prices'] = $custom_price['data'];
        } else {
            if (isset($custom_price['status']) && $custom_price['status'] == "error") {
                $error_bag[] = $custom_price['message'];
            }
            $data['custom_prices'] = [];
        }


        if (count($error_bag) > 0) {
            return view('browses.ecs.ecs_detail', $data)->withErrors($error_bag);
        }
        return view('browses.ecs.ecs_detail', $data);
    }

    public function generateQRCode($ecs_id, $connector_id = null)
    {
        $error_bag = [];

        $device = MyHelper::get(self::SOURCE, 'v1/ecs/' . $ecs_id);
        if (isset($device['status']) && $device['status'] == "success") {
            $device_detail = $device['data'];
        } else {
            if (isset($device['status']) && $device['status'] == "error") {
                return redirect('browse/ecs/' . $ecs_id)->withErrors($device['message'])->withInput();
            }
            return redirect('browse/ecs/' . $ecs_id)->withErrors("Something went wrong!")->withInput();
        }
        if ($device['data']['status'] == "NEW") {
            return redirect('browse/ecs/' . $ecs_id)->withErrors(['Cannot Print QR Code for New ECS'])->withInput();
        }

        if ($device['data']['location_id'] == 0 || $device['data']['num_of_connector'] == 0) {
            return redirect('browse/ecs/' . $ecs_id)->withErrors(['No Location / Connector found'])->withInput();
        }

        $connector_list = [];
        $connectors = MyHelper::get(self::SOURCE, 'v1/ecs/connector/' . $ecs_id);
        if (isset($connectors['status']) && $connectors['status'] == "success") {
            if ($connector_id != null) {
                foreach ($connectors['data'] as $connector) {
                    if ($connector['connector_id'] == $connector_id) {
                        if ($connector['qr_code'] == null) {
                            $error_bag[] = "No QrCode Value for connector number : " . $connector['connector_id'];
                        }
                        $connector_list[] = $connector;
                    }
                }
            } else {
                foreach ($connectors['data'] as $connector) {
                    if ($connector['qr_code'] == null) {
                        $error_bag[] = "No QrCode Value for connector number : " . $connector['connector_id'];
                    }
                    $connector_list[] = $connector;
                }
            }
        } else {
            if (isset($connectors['status']) && $connectors['status'] == "error") {
                $error_bag[] = $connectors['message'];
            }
        }

        if (count($error_bag) > 0) {
            return redirect('browse/ecs/' . $ecs_id)->withErrors($error_bag)->withInput();
        }

        $path = 'device/qr-code/' . $device_detail['ecs_id'];
        $qr_files = Storage::files($path);
        foreach ($connector_list as $key => $conn) {
            $filename = $device_detail['ecs_id'] . "_" . $conn['connector_id'];
            $qr_path = $path . "/" . $filename . ".png";
            if (!in_array($qr_path, $qr_files)) {
                $generated_qr_path = MyHelper::GenerateQR(empty($conn['qr_code']) ? "no-data" : $conn['qr_code'], $filename, $path, $device['data']['num_of_connector'] > 1 ? $device_detail['ecs_id'] . "-" . $conn['connector_id'] : $device_detail['ecs_id']);
                $connector_list[$key]['qr_path'] = $generated_qr_path;
            } else {
                $connector_list[$key]['qr_path'] = $qr_path;
            }
        }

        $data = ["device" => $device_detail, "connectors" => $connector_list];

        $filename = $connector_id == null ? $device_detail['ecs_id'] : ($device['data']['num_of_connector'] > 1 ? $device_detail['ecs_id'] . "-" . $connector_id : $device_detail['ecs_id']);

        $pdf = Pdf::loadView('pdf.qrcode', $data);
        return $pdf->download($filename . '.pdf');
    }

    public function assignECSLocation(AssignECSLocationRequest $request)
    {

        $payload = [
            "id" => intval($request->id_ecs),
            "location_id" => intval($request->location_id),
            "num_of_connector" => intval($request->num_of_connector),
        ];

        $save = MyHelper::post(self::SOURCE, 'v1/ecs/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Success Assign ECS to location']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function searchECS()
    {
        $search = request()->query("search");

        $url = 'v1/ecs/search';
        if (!empty($search)) {
            $url = 'v1/ecs/search?search=' . $search;
        }

        $ecs = MyHelper::get(self::SOURCE, $url);

        if (isset($ecs['status']) && $ecs['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['search ecs success'], 'data' => $ecs['data']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$ecs['message']]]);
        }
    }

    //TODO Development only, will remove soon!
    public function registerECS($ecs_id)
    {
        if (env('APP_ENV') == 'production') {
            return response()->json(['status' => 'fail', 'messages' => ['This Endpoint Is Closed']]);
        }

        $save = MyHelper::RegisterECS($ecs_id);

        if (isset($save['status']) && $save['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['register ecs success'], 'data' => $ecs_id]);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return response()->json(['status' => 'fail', 'messages' => [$save['message']]]);
            }
            return response()->json(['status' => 'fail', 'messages' => ['Something went wrong. Please try again.']]);
        }
    }

    public function updateECSPricing(Request $request, $id)
    {
        $payload = [
            "id" => intval($request->id),
            "pricing_group" => intval($request->custom_price),
        ];

        $save = MyHelper::post(self::SOURCE, 'v1/ecs/pricing/update', $payload);

        if ($request->type == 'ajax') {
            if (isset($save['status']) && $save['status'] == "success") {
                return response()->json(['status' => 'success', 'messages' => ['Success update ECS Pricing']]);
            } else {
                if (isset($save['status']) && $save['status'] == "error") {
                    return response()->json(['status' => 'fail', 'messages' => [$save['message']]]);
                }
                return response()->json(['status' => 'fail', 'messages' => [$save['message']]]);
            }
        }

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Success update ECS Pricing']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function listPricingECS()
    {
        $data = [
            'title'   => 'ECS',
            'sub_title'   => 'Pricing',
            'menu_active'    => 'pricing-ecs',
            'submenu_active' => 'pricing-ecs'
        ];

        $per_page = request()->query('per_page', 10);
        $page = request()->query('page', 1);
        $search = request()->query('search', '');

        $location_ids = [];
        $ecs = MyHelper::get(self::SOURCE, 'v1/ecs/pagination?per_page=' . $per_page . '&page=' . $page . '&search=' . $search);
        if (isset($ecs['status']) && $ecs['status'] == "success") {
            foreach ($ecs['data']['data'] as $location) {
                $location_ids[] = $location['location_id'];
            }
            $data['pagination'] = [
                'total_data' => $ecs['data']['total'],
                'per_page' => $ecs['data']['per_page'],
                'current_page' => $ecs['data']['current_page'],
                'last_page' => $ecs['data']['last_page'],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $string_location_ids = implode(",", $location_ids);

        $location_list = MyHelper::get(self::SOURCE, 'v1/location/list?location_ids=' . $string_location_ids);
        $location_data = [];
        if (isset($location_list['status']) && $location_list['status'] == "success") {
            if (count($location_list['data']) > 0) {
                foreach ($location_list['data'] as $location) {
                    $location_data[$location['id']] = $location;
                }
            }
        }

        foreach ($ecs['data']['data'] as $device) {
            $location_name = $device["location_id"] != 0 ? $location_data[$device["location_id"]]["name"] ?? "-" : "-";
            $data['devices'][] = [
                "id" => $device["id"],
                "ecs_id" => $device["ecs_id"],
                "status" => $device["status"],
                "pricing_group" => $device["pricing_group"],
                "num_of_connector" => $device["num_of_connector"] == 0 ? "-" : $device["num_of_connector"],
                "location_name" => $location_name,
            ];
        }

        $custom_price = MyHelper::get('core-transaction', 'v1/price-setting/custom-price/option');
        if (isset($custom_price['status']) && $custom_price['status'] == "success") {
            $data['custom_price'] = $custom_price['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.ecs.ecs_pricing', $data);
    }

    public function startCharging($ecs_id, $connector_id)
    {
        $reference = MyHelper::generateReference();
        $payload = [
            "connector_id" => intval($connector_id),
            "reference_id" => $reference,
            "ecs_id" => $ecs_id
        ];

        $send = MyHelper::startCharging($payload);

        if (isset($send['status']) && $send['status'] == "success") {
            return back()->withSuccess([$send['message']]);
        } else {
            if (isset($send['status']) && $send['status'] == "error") {
                return back()->withErrors($send['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function stopCharging($ecs_id, $connector_id, $reference)
    {
        $payload = [
            "connector_id" => intval($connector_id),
            "reference_id" => $reference,
            "ecs_id" => $ecs_id
        ];

        $send = MyHelper::stopCharging($payload);

        if (isset($send['status']) && $send['status'] == "success") {
            return back()->withSuccess([$send['message']]);
        } else {
            if (isset($send['status']) && $send['status'] == "error") {
                return back()->withErrors($send['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function ajaxGetConnectors($id)
    {
        $connectors = MyHelper::get(self::SOURCE, 'v1/ecs/connector/' . $id);

        if (isset($connectors['status']) && $connectors['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['search ecs success'], 'data' => $connectors['data']]);
        } else {
            if (isset($connectors['status']) && $connectors['status'] == "error") {
                return response()->json(['status' => 'fail', 'messages' => [$connectors['message']]]);
            }
            return response()->json(['status' => 'fail', 'messages' => ["something went wrong!"]]);
        }
    }
}
