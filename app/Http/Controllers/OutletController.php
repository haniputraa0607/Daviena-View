<?php

namespace App\Http\Controllers;

use App\Http\Requests\Outlet\Create;
use App\Lib\MyHelper;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    private string $path = 'be/outlet/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Outlet',
            'sub_title'         => 'List',
            'menu_active'       => 'outlet',
        ];

        return view('pages.outlet.index', $data);
    }

    public function create()
    {
        $districts = MyHelper::get('indonesia/districts');
        $data = [
            'title'             => 'Create Outlet',
            'sub_title'         => 'List',
            'menu_active'       => 'outlet',
            'districts'         => $districts['data'],
        ];

        return view('pages.outlet.create', $data);
    }

    public function store(Request $request)
    {

        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('outlet')->withSuccess(['New Outlet successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $districts = MyHelper::get('indonesia/districts');
        $data = [
            'title'             => 'CMS Detail Outlet',
            'sub_title'         => 'Detail',
            'districts'         => $districts['data'],
        ];

        $outlet = MyHelper::get($this->path . $id);
        if (isset($outlet['status']) && $outlet['status'] == "success") {
            $data['detail'] = $outlet['result'];
        } else {
            return back()->withErrors($outlet['error'])->withInput();
        }

        return view('pages.outlet.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $save = MyHelper::patch($this->path . $id, $request->except('_token'));

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('outlet')->withSuccess(['CMS Outlet detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
            }
            return back()->withFragment('#tab_detail')->withErrors($save['error'])->withInput();
        }
    }

    public function deleteOutlet($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Outlet deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
