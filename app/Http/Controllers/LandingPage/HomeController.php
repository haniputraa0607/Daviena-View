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
            'image' => $request->file('image'),
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

    public function productFinest(Request $request)
    {
        $data = [
            'title' => 'Product Trending'
        ];
        $product = MyHelper::get('be/product');
        $detail = MyHelper::get('be/product_finest');
        if (isset($detail['status']) && $detail['status'] == 'success') {
            $data['detail'] = $detail['result'];
            $data['products'] = $product['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.landing_page.home.product_finest', $data);
    }

    public function productFinestUpdate(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post('be/product_finest', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/home/product_finest')->withSuccess(['Product Finest successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function officialPartner(Request $request)
    {
        $data = [
            'title' => 'Article Recommendation'
        ];
        $partner = MyHelper::get('be/partner_equal');
        $detail = MyHelper::get('be/official_partner_home');
        if (isset($detail['status']) && $detail['status'] == 'success') {
            $data['detail'] = $detail['result'];
            $data['partners'] = $partner['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.landing_page.home.official_partner', $data);
    }

    public function officialPartnerUpdate(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post('be/official_partner_home', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/home/official_partner')->withSuccess(['Official Partner Home successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function articleRecommendation()
    {
        $data = [
            'title' => 'Article Recommendation'
        ];
        $article = MyHelper::get('be/article');
        $detail = MyHelper::get('be/article_recommendation');
        if (isset($detail['status']) && $detail['status'] == 'success') {
            $data['detail'] = $detail['result'];
            $data['articles'] = $article['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.landing_page.home.article_recommendation', $data);
    }

    public function articleRecommendationUpdate(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post('be/article_recommendation', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('landing_page/home/article_recommendation')->withSuccess(['Article Recommendation successfully updated.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    
}
