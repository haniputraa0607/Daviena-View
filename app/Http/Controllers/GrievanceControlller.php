<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class GrievanceControlller extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Grievance',
            'sub_title'         => 'List',
            'menu_active'       => 'grievance',
        ];
        $grievance = MyHelper::get('be/grievance');
        if (isset($grievance['status']) && $grievance['status'] == "success") {
            $data['grievances'] = $grievance['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.grievance.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Grievance',
            'sub_title'         => 'List',
            'menu_active'       => 'grievance',
        ];

        return view('pages.grievance.create', $data);
    }

    public function store(Request $request)
    {
        $payload = [
            "grievance_name" => $request->name,
            "description"  => $request->description,
            "is_active" => $request->is_active ?? '0',
        ];

        $save = MyHelper::post('be/grievance', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('grievance')->withSuccess(['New Grievance successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Grievance',
            'sub_title'         => 'Detail',
        ];
        $grievance = MyHelper::get('be/grievance/' . $id);
        if (isset($grievance['status']) && $grievance['status'] == "success") {
            $data['detail'] = $grievance['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.grievance.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = [
            "grievance_name" => $request->name,
            "description"  => $request->description,
            "is_active" => $request->is_active ?? '0',
        ];

        $save = MyHelper::patch('be/grievance/' . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('grievance')->withSuccess(['CMS Grievance detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteGrievance($id)
    {
        $delete = MyHelper::deleteApi('be/grievance/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Grievance deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
