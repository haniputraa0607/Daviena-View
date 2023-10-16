<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Lib\MyHelper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private string $path = 'be/order/';

    public function index()
    {
        $data = [
            'title'             => 'Order',
            'sub_title'         => 'List',
            'menu_active'       => 'order',
        ];
        return view('transactions.order.index', $data);
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Order',
            'sub_title'         => 'Detail',
            'menu_active' => 'order'
        ];
        $detail = MyHelper::get($this->path . $id);

        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('transactions.order.detail', $data);
    }
}
