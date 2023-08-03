<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Partner',
            'sub_title'         => 'List',
            'menu_active'       => 'partner',
        ];
        $partner = MyHelper::get('be/partner');
        if (isset($partner['status']) && $partner['status'] == "success") {
            $data['partners'] = $partner['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.partner.index', $data);
    }

    public function create()
    {
        return view('pages.partner.create', [
            'title'             => 'Create Partmer',
            'sub_title'         => 'List',
            'menu_active'       => 'partner',
        ]);
    }

    public function store(Request $request)
    {
        $payload = [
            "partner_code" => $request->partner_code,
            "partner_name" => $request->partner_name,
            "partner_email" => $request->partner_email,
            "partner_phone" => $request->partner_phone,
            "partner_address" => $request->partner_address,
        ];
        $save = MyHelper::post('be/partner', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('partner')->withSuccess(['New Partner successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Partner',
            'sub_title'         => 'Detail',
        ];
        $partner = MyHelper::get('be/partner/' . $id);
        if (isset($partner['status']) && $partner['status'] == "success") {
            $data['detail'] = $partner['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.partner.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = [
            "partner_code" => $request->partner_code,
            "partner_name" => $request->partner_name,
            "partner_email" => $request->partner_email,
            "partner_phone" => $request->partner_phone,
            "partner_address" => $request->partner_address,
        ];
        $save = MyHelper::patch('be/partner/' . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('partner')->withSuccess(['CMS Partner detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deletePartner($id)
    {
        $delete = MyHelper::deleteApi('be/partner/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Partner deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
