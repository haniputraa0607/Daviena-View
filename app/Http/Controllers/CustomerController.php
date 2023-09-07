<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Lib\MyHelper;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private string $path = 'be/customer/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Customer',
            'sub_title'         => 'List',
            'menu_active'       => 'customer',
        ];
        return view('pages.customer.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Customer',
            'sub_title'         => 'List',
            'menu_active'       => 'customer',
        ];

        return view('pages.customer.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        // dd($payload);

        $save = MyHelper::post($this->path, $payload);
        // dd($payload,$save);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('customer')->withSuccess(['New Customer successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Customer',
            'sub_title'         => 'Detail',
            'menu_active'       => 'customer',
        ];
        $customer = MyHelper::get($this->path . $id);
        if (isset($customer['status']) && $customer['status'] == "success") {
            $data['detail'] = $customer['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.customer.detail', $data);
    }

    public function update(Request $request, $id)
    {

        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('customer')->withSuccess(['CMS Customer detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteCustomer($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Customer deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
