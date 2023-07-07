<?php

namespace App\Http\Controllers\Transactions\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Transactions\Settings\UpdateGlobalPriceRequest;
use App\Http\Requests\Transactions\Settings\UpdateMDRFeeRequest;
use App\Http\Requests\Transactions\Settings\AddCustomPriceRequest;
use App\Http\Requests\Transactions\Settings\UpdateCustomPriceRequest;
use App\Lib\MyHelper;

class PriceSettingController extends Controller
{
    const SOURCE = 'core-transaction';

    public function getPriceSetting() {
        $data = [
            'title'   => 'Price Setting',
            'sub_title'   => 'Price Setting',
            'menu_active'    => 'setting-price',
            'submenu_active' => 'setting-price'
        ];

        if (!in_array(32, session('granted_features')) && !in_array(34, session('granted_features')) && !in_array(37, session('granted_features')) && session('user_role') != 'super_admin') {
            return redirect('home')->withErrors(['You don\'t have permission to access the page.']);
        }

        $global_price = MyHelper::get(self::SOURCE,'v1/price-setting/global-price');
        if (isset($global_price['status']) && $global_price['status'] == "success") {
            $data['global_price'] = $global_price['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $mdr_fee = MyHelper::get(self::SOURCE,'v1/price-setting/mdr-fee');
        if (isset($mdr_fee['status']) && $mdr_fee['status'] == "success") {
            $data['mdr_fee'] = $mdr_fee['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $custom_price = MyHelper::get(self::SOURCE,'v1/price-setting/custom-price');
        if (isset($custom_price['status']) && $custom_price['status'] == "success") {
            $data['custom_price'] = $custom_price['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('transactions.settings.price_setting', $data);
    }

    public function UpdateGlobalPrice(UpdateGlobalPriceRequest $request) {
        $payload = [
            "price" => (float) $request->price,
            "tax" => (float) $request->tax
        ];

        $save = MyHelper::post(self::SOURCE,'v1/price-setting/global-price', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Global price setting updated successfully.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function UpdateMDRFee(UpdateMDRFeeRequest $request) {
        $payload = [];
        foreach ($request->formula as $key => $formula) {
            $payload['setting'][] = [
                "key" => $key,
                "formula" => $formula
            ];
        }

        $save = MyHelper::post(self::SOURCE,'v1/price-setting/mdr-fee', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withFragment("#mdr_fee")->withSuccess(['MDR Fee setting updated successfully.']);
        } else {
            return back()->withFragment("#mdr_fee")->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function AddCustomPrice(AddCustomPriceRequest $request) { //TODO make request validation
        $payload = [
            "name" => $request->name,
            "price" => (float) $request->price
        ];

        $save = MyHelper::post(self::SOURCE,'v1/price-setting/custom-price/add', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withFragment("#custom_price")->withSuccess(['Custom Price added successfully.']);
        } else {
            return back()->withFragment("#custom_price")->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function UpdateCustomPrice(UpdateCustomPriceRequest $request) { //TODO make request validation
        $payload = [
            "id" => $request->id,
            "name" => $request->name,
            "price" => (float) $request->price
        ];

        $save = MyHelper::post(self::SOURCE,'v1/price-setting/custom-price/update', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withFragment("#custom_price")->withSuccess(['Custom Price updated successfully.']);
        } else {
            return back()->withFragment("#custom_price")->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function DeleteCustomPrice($id) {
        $delete= MyHelper::get(self::SOURCE,'v1/price-setting/custom-price/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Custom Price deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
