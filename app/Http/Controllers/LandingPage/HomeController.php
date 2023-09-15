<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\MyHelper;

class HomeController extends Controller
{
    public function treatmentConsultation()
    {
        $data = [
            'title'   => 'Treatment and Consultation',
        ];
        $detail = MyHelper::curlApi('landing-page/treatment_consultation', 'GET');
        if (isset($detail['status']) && $detail['status'] == "success") {
            $data['detail'] = $detail['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.landing_page.home.treatment_consultation', $data);
    }

    public function treatmentConsultationUpdate(Request $request)
    {
        $payload = [
            'title' => $request->title,
            'description' => $request->description,
            'image_front' => $request->file('image_front'),
            'image_behind' => $request->file('image_behind'),
        ];
        $upload = MyHelper::curlApi('landing-page/treatment_consultation', 'POST_IMAGE', $payload);
        return redirect('landing_page/home/treatment_and_consultation')->withSuccess(['Treatment and Consultation has been updated.']);
    }

    public function productTrending(Request $request)
    {
        $data = [
            'title' => 'Product Trending'
        ];
        $product = MyHelper::get('be/product');
        $detail = MyHelper::get('be/product_trending');
        if (isset($detail['status']) && $detail['status'] == 'success') {
            $data['detail'] = $detail['result'];
            $data['products'] = $product['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.landing_page.home.product_trending', $data);
    }

    public function productTrendingUpdate(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post('be/product_trending', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/home/product_trending')->withSuccess(['Product Trending successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }
}
