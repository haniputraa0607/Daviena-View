<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\MyHelper;

class OfficialPartnerController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Official Partner',
            'menu_active' => 'official_partner',
        ];
        $detail = MyHelper::get('be/official_partner', 'GET');
        // dd($detail);
        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.landing_page.official_partner', $data);
    }

    public function update(Request $request){
        $payload = $request->except('_token');
        $save = MyHelper::post('be/official_partner', $payload);
        // dd($save);/
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/official_partner')->withSuccess(['Official Partner successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }
}