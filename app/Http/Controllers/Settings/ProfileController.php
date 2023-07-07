<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Lib\MyHelper;

class ProfileController extends Controller
{
    const SOURCE = 'core-user';

    public function getProfileSetting() {
        $data = [
            'title'             => 'My Profile',
            'menu_active'       => 'setting-profile',
            'submenu_active'    => 'setting-profile'
        ];

        $user_id = session('user_id');

        $detail = MyHelper::get(self::SOURCE,'v1/user/detail/' . $user_id);
        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $per_page = request()->query('per_page', 10);
        $page = request()->query('page', 1);
        $log = MyHelper::get(self::SOURCE,'v1/cms-activity-log/pagination?user_id=' . $user_id . '&per_page=' . $per_page . '&page=' . $page);
        if (isset($log['status']) && $log['status'] == "success") {
            $data['user_log'] = $log['data']['data'];
            $data['pagination'] = [
                'total_data' => $log['data']['total'],
                'per_page' => $log['data']['per_page'],
                'current_page' => $log['data']['current_page'],
                'last_page' => $log['data']['last_page'],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('settings.profile.my-profile', $data);
    }

    public function updateProfileSetting(UpdateProfileRequest $request) {
        if ($request->name) {
            $redirect_tab = "#tab_detail";
            $payload = [
                'id' => $request->id,
                'name' => $request->name,
                'email' => $request->email,
            ];
        } elseif ($request->old_password) {
            $redirect_tab = "#tab_password";
            $payload = [
                'id' => $request->id,
                'old_password' => $request->old_password,
                'new_password' => $request->new_password,
            ];
        } else {
            $redirect_tab = "";
        }

        $save = MyHelper::post(self::SOURCE,'v1/user/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            if ($request->name) {
                $sessionData = session()->all();
                $sessionData['user_name'] = $request->name;
                $sessionData['user_email'] = $request->email;
                session($sessionData);
            }
            return redirect('profile')->withFragment($redirect_tab)->withSuccess(['Profile has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withFragment($redirect_tab)->withErrors($save['message'])->withInput();
            }
            return back()->withFragment($redirect_tab)->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
