<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Lib\MyHelper;
use App\Traits\ApiResponse;
use GoogleReCaptchaV3;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        // $captcha = GoogleReCaptchaV3::verifyResponse($request->input('g-recaptcha-response'))->isSuccess();
        // if (!$captcha) {
        //     return redirect()->back()->withErrors(['Recaptcha failed']);
        // }
        $login = MyHelper::postLogin($request);

        if (isset($login['access_token'])) {
            session([
                'access_token'  => 'Bearer ' . $login['access_token']
            ]);

            $userData = MyHelper::get('be/user/detail');
            // dd($userData);

            if (isset($userData['status']) && $userData['status'] == 'success' && !empty($userData['result'])) {
                $dataUser = $userData['result'];
            }

            session([
                'access_token'      => 'Bearer ' . $login['access_token'],
                'user_id'           => $dataUser['user']['id'],
                'user_name'         => $dataUser['user']['name'],
                'user_email'        => $dataUser['user']['email'],
                'user_role'         => $dataUser['user']['admin_id'],
                'granted_features'  => $dataUser['features'],
            ]);
            return redirect('home');
        }
        return  redirect('login')->withErrors(['invalid_credentials' => 'Invalid username / password'])->withInput();
    }

    public function getHome()
    {
        $data = [
            'title'             => 'Home',
            'menu_active'       => 'home',
            'submenu_active'    => ''
        ];
        return view('home', $data);
    }

    public function conncetionTest()
    {

        $response = Http::asMultipart()
            ->withHeaders([
                'Accept' => 'application/json',
                'X-CSRF-TOKEN' => '',
            ])
            ->post('https://api-daviena.belum.live/oauth/token', [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'TPpNwS8QBqdVKL7cYkOv1EWfcl1Vqgs8Ks9CciF3',
                'scope' => 'be',
                'username' => '08111222334',
                'password' => '777777',
            ]);

        // Handle the response data here
        $data = $response->json();
        dd($data);
    }
}
