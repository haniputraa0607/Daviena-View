<?php

namespace App\Http\Controllers\Browses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use App\Http\Requests\Browses\MobileUser\UpdateStatusMobileUserRequest;

class MobileUserController extends Controller
{
    const SOURCE = 'core-user';

    public function getMobileUserList()
    {
        $data = [
            'title'             => 'Mobile Users',
            'sub_title'         => 'List',
            'menu_active'       => 'browse-mobile-user',
            'submenu_active'    => 'browse-mobile-user'
        ];

        $mobile_user = MyHelper::get(self::SOURCE, 'v1/mobile-user');
        if (isset($mobile_user['status']) && $mobile_user['status'] == "success") {
            $data['mobile_users'] = $mobile_user['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.mobile-user.mobile_user_list', $data);
    }

    public function getMobileUserDetail($id)
    {
        $data = [
            'title'             => 'Mobile Users',
            'sub_title'         => 'Detail',
            'menu_active'       => 'browse-mobile-user',
            'submenu_active'    => 'browse-mobile-user'
        ];

        $mobile_user = MyHelper::get(self::SOURCE, 'v1/mobile-user/detail/' . $id);
        if (isset($mobile_user['status']) && $mobile_user['status'] == "success") {
            $data['detail'] = $mobile_user['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $cas_point = MyHelper::get('core-transaction', 'v1/cas-point/user/' . $id);
        if (isset($cas_point['status']) && $cas_point['status'] == "success") {
            $data['cas_point'] = $cas_point['data']['cas_point_amount'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $vehicle = MyHelper::get('core-api', 'v1/user-vehicle?user_id=' . $id);
        if (isset($vehicle['status']) && $vehicle['status'] == "success") {
            $data['vehicle_count'] = count($vehicle['data']);
            $data['vehicles'] = $vehicle['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $per_page = request()->query('per_page', 10);
        $page = request()->query('page', 1);

        $date_start = request()->query('date_start', "");
        $date_end = request()->query('date_end', "");

        $transaction = MyHelper::get('core-transaction', 'v1/transaction/user/' . $id . '?per_page=' . $per_page . '&page=' . $page . '&date_start=' . $date_start . '&date_end=' . $date_end);
        if (isset($transaction['status']) && $transaction['status'] == "success") {
            $data['transactions'] = $transaction['data']['data'];
            $data['pagination'] = [
                'total_data' => $transaction['data']['total'],
                'per_page' => $transaction['data']['per_page'],
                'current_page' => $transaction['data']['current_page'],
                'last_page' => $transaction['data']['last_page'],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('browses.mobile-user.mobile_user_detail', $data);
    }

    public function updateStatusMobileUser(UpdateStatusMobileUserRequest $request)
    {
        if ($request->status == 'activate') {
            $status = true;
        } else {
            $status = false;
        }

        $payload = [
            'id'            => $request->id,
            'is_active'     => $status
        ];

        $update = MyHelper::post(self::SOURCE, 'v1/mobile-user/status/update', $payload);

        if (isset($update['status']) && $update['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Mobile user status updated successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$update['message']]]);
        }
    }

    public function getMobileUserTransactionDetail($user_id, $trx_id)
    {
        $data = [
            'title'             => 'Mobile Users',
            'sub_title'         => 'Transaction',
            'menu_active'       => 'browse-mobile-user',
            'submenu_active'    => 'browse-mobile-user'
        ];

        $transaction = MyHelper::get('core-transaction', 'v1/transaction/' . $trx_id);
        if (isset($transaction['status']) && $transaction['status'] == "success") {
            $data['details'] = $transaction['data'];

            $device_detail = MyHelper::get('core-api', 'v1/ecs/connector/detail/' . $transaction['data']['uuid']);
            if (isset($device_detail['status']) && $device_detail['status'] == "success") {
                $data['device'] = $device_detail['data'];
            } else {
                return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
            }
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $data['user_id'] = $user_id;
        return view('browses.mobile-user.mobile_user_transaction_detail', $data);
    }
}
