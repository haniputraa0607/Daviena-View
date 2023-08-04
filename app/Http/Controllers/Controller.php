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
        if (isset($login['error'])) {
            $loginClient =  MyHelper::postLoginClient();

            if (isset($loginClient['access_token'])) {
                session([
                    'access_token'  => 'Bearer ' . $loginClient['access_token']
                ]);
            }
            return redirect('login')->withErrors(['invalid_credentials' => 'Invalid username / password'])->withInput();
        } else {
            if (isset($login['status']) && $login['status'] == "fail") {
                $loginClient =  MyHelper::postLoginClient();

                if (isset($loginClient['access_token'])) {
                    session([
                        'access_token'  => 'Bearer ' . $loginClient['access_token']
                    ]);
                }

                return redirect('login')->withErrors($login['messages'])->withInput();
            } else {
                session([
                    'access_token'  => 'Bearer ' . $login['access_token'],
                    'user_name'      => $request->input('username'),
                ]);

                // $userData = MyHelper::get('be/user/');
                $userData = MyHelper::get('be/user/detail');
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
        }
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
}
