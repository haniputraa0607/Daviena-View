<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class PartnerEqualController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Partner',
            'sub_title'         => 'List',
            'menu_active'       => 'partner_equal',
        ];
        $partner = MyHelper::get('be/partner_equal');
        if (isset($partner['status']) && $partner['status'] == "success") {
            $data['partners'] = $partner['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.partner_equal.index', $data);
    }

    public function create()
    {
        return view('pages.partner_equal.create', [
            'title'             => 'Create Partmer',
            'sub_title'         => 'List',
            'menu_active'       => 'partner',
        ]);
    }

    public function store(Request $request)
    {
        $payload = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "store_name" => $request->store_name,
            "store_address" => $request->store_address,
            "store_city" => $request->store_city,
            "url_instagram" => $request->url_instagram,
            "url_tiktok" => $request->url_tiktok,
            "url_tokopedia" => $request->url_tokopedia,
            "url_shopee" => $request->url_shopee,
            "url_buka_lapak" => $request->url_buka_lapak
        ];
        $save = MyHelper::post('be/partner_equal', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('partner_equal')->withSuccess(['New Partner successfully added.']);
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
        $partner = MyHelper::get('be/partner_equal/' . $id);
        if (isset($partner['status']) && $partner['status'] == "success") {
            $search_ig = 'Instagram';
            $search_tk = 'Tiktok';
            $search_tp = 'Tokopedia';
            $search_sp = 'Shopee';
            $search_bk = 'Bukalapak';
            $tiktok_data = array_filter($partner['result']['sosial_media'], function ($item) use ($search_tk) {
                return $item['type'] === $search_tk;
            });
            $ig_data = array_filter($partner['result']['sosial_media'], function ($item) use ($search_ig) {
                return $item['type'] === $search_ig;
            });
            $tp_data = array_filter($partner['result']['sosial_media'], function ($item) use ($search_tp) {
                return $item['type'] === $search_tp;
            });
            $sp_data = array_filter($partner['result']['sosial_media'], function ($item) use ($search_sp) {
                return $item['type'] === $search_sp;
            });
            $bk_data = array_filter($partner['result']['sosial_media'], function ($item) use ($search_bk) {
                return $item['type'] === $search_bk;
            });
            $data['detail'] = $partner['result'];
            $data['sosial_media'] = [
                'tiktok' => $tiktok_data ? reset($tiktok_data) : [] ,
                'instagram' => $ig_data ? reset($ig_data) : [],
                'tokopedia' => $tp_data ? reset($tp_data) : [],
                'shopee' => $sp_data ? reset($sp_data) : [],
                'bukalapak' => $bk_data ? reset($bk_data) : [],
            ];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.partner_equal.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = [
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "store_name" => $request->store_name,
            "store_address" => $request->store_address,
            "store_city" => $request->store_city,
            "url_instagram" => $request->url_instagram,
            "url_tiktok" => $request->url_tiktok,
            "url_tokopedia" => $request->url_tokopedia,
            "url_shopee" => $request->url_shopee,
            "url_buka_lapak" => $request->url_buka_lapak
        ];
        $save = MyHelper::patch('be/partner_equal/' . $id, $payload);
        // dd($save);
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
        $delete = MyHelper::deleteApi('be/partner_equal/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Partner deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
