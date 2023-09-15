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
                'access_token'  => 'Bearer ' . $login['access_token'],
                'expires_in'         => $login['expires_in'],
            ]);
            $user_login = MyHelper::get('be/user/detail')['result'];

            session([
                'user_id'           => $user_login['user']['id'] ?? null,
                'user_name'         => $user_login['user']['name'] ?? null,
                'user_email'        => $user_login['user']['email'] ?? null,
                'user_role'         => $user_login['user']['admin_id'] ?? null,
                'granted_features'  => $user_login['features'] ?? null,
            ]);

            return redirect('home');
        } else {
            if (isset($login['status']) && $login['status'] == "error") {
                if ($login['message'] == 'wrong password' || $login['message'] == 'user not found') {
                    return redirect('login')->withErrors(['invalid_credentials' => 'Invalid username / password'])->withInput();
                } elseif ($login['message'] == 'cannot login to suspended account') {
                    return redirect('login')->withErrors(['Cannot login. Account suspended.'])->withInput();
                } elseif ($login['message'] == 'no role assigned to this account. Please contact Super Admin') {
                    return redirect('login')->withErrors(['No Role assigned to this account. Please contact Super Admin.'])->withInput();
                } elseif ($login['message'] == 'cannot login to inactive / deleted role account. Please contact Super Admin') {
                    return redirect('login')->withErrors(['Cannot login to inactive / deleted role account. Please contact Super Admin.'])->withInput();
                } else {
                    return redirect('login')->withErrors($login['message'])->withInput();
                }
            } else {
                return redirect('login')->withErrors(['invalid_credentials' => 'Invalid username / password'])->withInput();
            }
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
