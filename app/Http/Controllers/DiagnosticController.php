<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    public function index()
    {
        $data = [
            'title'             => 'Manage Diagnostic',
            'sub_title'         => 'List',
            'menu_active'       => 'diagnostic',
        ];
        $diagnostic = MyHelper::get('be/diagnostic');
        if (isset($diagnostic['status']) && $diagnostic['status'] == "success") {
            $data['diagnostics'] = $diagnostic['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.diagnostic.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Diagnostic',
            'sub_title'         => 'List',
            'menu_active'       => 'diagnostic',
        ];

        return view('pages.diagnostic.create', $data);
    }

    public function store(Request $request)
    {
        $payload = [
            "diagnostic_name" => $request->name,
            "description"  => $request->description,
            "is_active" => $request->is_active ?? '0',
        ];

        $save = MyHelper::post('be/diagnostic', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('diagnostic')->withSuccess(['New Diagnostic successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Diagnostic',
            'sub_title'         => 'Detail',
        ];
        $diagnostic = MyHelper::get('be/diagnostic/' . $id);
        if (isset($diagnostic['status']) && $diagnostic['status'] == "success") {
            $data['detail'] = $diagnostic['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.diagnostic.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = [
            "diagnostic_name" => $request->name,
            "description"  => $request->description,
            "is_active" => $request->is_active ?? '0',
        ];

        $save = MyHelper::patch('be/diagnostic/' . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('diagnostic')->withSuccess(['CMS Diagnostic detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteDiagnostic($id)
    {
        $delete = MyHelper::deleteApi('be/diagnostic/' . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Diagnostic deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
