<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiSettingController extends Controller
{
    public function commmision_doctor(): JsonResponse
    {
        $setting = Setting::where('key', 'doctor_commission')->first();
        return $this->ok("success", $setting);
    }

    public function commmision_doctor_action(Request $request): JsonResponse
    {
        $value_persen = $request->value/100;
        $setting = Setting::where('key', 'doctor_commission')->first();
        if($setting){
            $setting->update(['value' => $value_persen]);
        } else {
            Setting::create([
                'key' => 'doctor_commission',
                'value' => $value_persen
            ]);
        }
        return $this->ok("success", true);
    }
}
