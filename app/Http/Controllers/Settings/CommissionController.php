<?php

namespace App\Http\Controllers\Settings;

use App\Lib\MyHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{
    private string $path = 'be/setting/commission-global';

    public function index()
    {
        $data = [
            'title'             => 'Setting Commision Fee Global',
            'sub_title'         => 'Setting',
            'menu_active'       => 'commission-doctor-global',
        ];
        $commission = MyHelper::get($this->path);

        if (isset($commission['status']) && $commission['status'] == "success") {
            $data['detail'] = $commission['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('settings.commission.index', $data);
    }

    public function action(Request $request)
    {
        $payload = [
            "value" => $request->value,
        ];
        $save = MyHelper::post($this->path, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/commission-doctor-global')->withSuccess(['CMS Commission Global detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors($save['error'])->withInput();
        }
    }

}
