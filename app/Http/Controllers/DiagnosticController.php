<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    private string $path = 'be/diagnostic/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Diagnostic',
            'sub_title'         => 'List',
            'menu_active'       => 'diagnostic',
        ];
        $diagnostic = MyHelper::get($this->path);
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
        $payload = $request->except('_token');

        $save = MyHelper::post($this->path, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('diagnostic')->withSuccess(['New Diagnostic successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Diagnostic',
            'sub_title'         => 'Detail',
        ];
        $diagnostic = MyHelper::get($this->path . $id);
        if (isset($diagnostic['status']) && $diagnostic['status'] == "success") {
            $data['detail'] = $diagnostic['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.diagnostic.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('diagnostic')->withSuccess(['CMS Diagnostic detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteDiagnostic($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Diagnostic deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
