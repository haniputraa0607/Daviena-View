<?php

namespace App\Http\Controllers\Transactions\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Transactions\Settings\UpdatePaymentMethodLogoRequest;
use App\Http\Requests\Transactions\Settings\UpdateAvailablePaymentSettingRequest;
use App\Lib\MyHelper;
use Exception;

class PaymentController extends Controller
{
    const SOURCE = 'core-transaction';
    const ROOTPATH = 'payment-method';

    public function getAvailablePaymentSetting() {
        $data = [
            'title'   => 'Transaction',
            'sub_title'   => 'Payment Method',
            'menu_active'    => 'setting-payment-method',
            'submenu_active' => 'setting-payment-method'
        ];

        // $availablePayment = config('payment_method');
        $payment_method = MyHelper::get(self::SOURCE,'v1/payment-method');
        if (isset($payment_method['status']) && $payment_method['status'] == "success") {
            $data['payments'] = $payment_method['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        $existing_image = [];
        foreach ($payment_method['data'] as $value) {
            array_push($existing_image, $value['logo']);
        }

        //Delete image file that deleted images from database
        MyHelper::deleteImageNotExist('payment-method/logo', $existing_image);

        return view('transactions.settings.payment_method', $data);
    }

    public function updateAvailablePaymentSetting(UpdateAvailablePaymentSettingRequest $request) {
        $payload = [];
        foreach ($request->ids as $order => $payment) {
            if (isset($request->status[$payment])) {
                $status = $request->status[$payment]['status'] == "1" ? true : false;
            } else {
                $status = false;
            }

            $payload['data'][] = [
                "id" => $payment,
                "status" => $status,
                "order_no" => $order
            ];
        }

        $save = MyHelper::post(self::SOURCE,'v1/payment-method', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Payment method setting has been updated.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function updateLogoPaymentMethod(UpdatePaymentMethodLogoRequest $request){
        try {
            $path = 'logo';
            $fileName = MyHelper::createFilename($request->file);
            $uploadedFile = MyHelper::uploadFile($request->file, self::ROOTPATH, $path, $fileName);

            $payload = [
                "id" => $request->id,
                "logo"  => $uploadedFile['path']
            ];
        } catch (Exception $error) {
            return back()->withErrors($error->getMessage())->withInput();
        }

        $save = MyHelper::post(self::SOURCE,'v1/payment-method/update/logo', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return back()->withSuccess(['Payment method logo updated successfully']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }
}
