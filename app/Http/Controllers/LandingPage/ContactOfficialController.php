<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\MyHelper;

class ContactOfficialController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Contact Official',
            'menu_active' => 'contact_official',
        ];
        $detail = MyHelper::get('be/contact_official', 'GET');
        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.landing_page.contact_official', $data);
    }

    public function update(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post('be/contact_official', $payload);
        // dd($save);/
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/contact_official')->withSuccess(['Official Partner successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function consultationOrdering()
    {
        $data = [
            'title'   => 'Consultation & Ordering',
            'menu_active' => 'consultation_ordering',
        ];
        $detail = MyHelper::get('be/consultation_ordering', 'GET');
        // dd($detail);
        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.landing_page.consultation_ordering', $data);
    }

    
    public function consultationOrderingUpdate(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post('be/consultation_ordering', $payload);
        // dd($save);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/consultation_ordering')->withSuccess(['Consultation Ordering successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }
}
