<?php

namespace Modules\Consultation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Lib\MyHelper;

class ConsultationController extends Controller
{
    public function index()
    {
        return view('consultation::index');
    }

    public function consultationList() {
        $data = [];
        $data = [
            'title'          => 'Consultation',
            'menu_active'    => 'consultation',
            'submenu_active' => 'consultation-list'
        ];

        $list = MyHelper::get('consultation');

        if (isset($list['status']) && $list['status'] == 'success') {
            $data['list'] = $list['result'];
        } else {
            return view('consultation::consultationList', $data)->withErrors($list['messages']);
        }

        return view('consultation::consultationList', $data);
    }

    public function consultationSearch() {
        $data = [];
        $data = [
            'title'   => 'Consultation',
            'menu_active'    => 'consultation',
            'submenu_active' => 'consultation-search'
        ];

        $getList = MyHelper::get('consultation');
        if($getList['status'] == 'success') $data['list'] = $getList['result']; else $data['list'] = null;

        $getCity = MyHelper::get('city/list');
        if($getCity['status'] == 'success') $data['city'] = $getCity['result']; else $data['city'] = null;

        $getProvince = MyHelper::get('province/list');
        if($getProvince['status'] == 'success') $data['province'] = $getProvince['result']; else $data['province'] = null;
        
		$getCourier = MyHelper::get('courier/list');
		if($getCourier['status'] == 'success') $data['couriers'] = $getCourier['result']; else $data['couriers'] = null;
		
		// return $data;
        return view('consultation::consultationSearch', $data);
    }

    public function consultationFilter(Request $request) {
        $post = $request->all();

        if (empty($post)) {
            return redirect('consultation/search');
        }

        $data = [];
        $data = [
            'title'   => 'Consultation',
            'sub_title'   => 'Filter',
            'menu_active'    => 'consultation',
            'submenu_active' => 'consultation-search'
        ];

        $filter = MyHelper::post('consultation/filter', $post);
       
        if (isset($filter['status']) && $filter['status'] == 'success') {
            $data['list']       = $filter['data'];
            $data['conditions'] = $filter['conditions'];
            $data['count']      = $filter['count'];
            $data['rule']       = $filter['rule'];
            $data['search']     = $filter['search'];
            return view('consultation::consultationSearch', $data);

        } elseif (isset($filter['status']) && $filter['status'] == 'fail') {
            $data['list']       = $filter['data'];
            $data['conditions'] = $filter['conditions'];
            $data['count']      = $filter['count'];
            $data['rule']       = $filter['rule'];
            $data['search']     = $filter['search'];

            return view('consultation::consultationSearch', $data);

        }
    }
    
}
