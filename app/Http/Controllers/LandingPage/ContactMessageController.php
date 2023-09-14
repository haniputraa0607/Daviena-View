<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Lib\MyHelper;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    private string $path = 'be/contact_message/';

    public function index()
    {
        $data = [
            'title'             => 'Contact Message',
            'sub_title'         => 'List',
            'menu_active'       => 'contact_message',
        ];
        return view('pages.landing_page.contact_message.index', $data);
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Contact Message',
            'sub_title'         => 'Detail',
            'menu_active' => 'contact_message'
        ];
        $detail = MyHelper::get($this->path . $id);

        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.landing_page.contact_message.detail', $data);
    }
}
