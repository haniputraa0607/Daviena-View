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
                session(['access_token'  => 'Bearer ' . $loginClient['access_token']
                ]);
            }
            return redirect('login')->withErrors(['invalid_credentials' => 'Invalid username / password'])->withInput();
        } else {
            if (isset($login['status']) && $login['status'] == "fail") {
                $loginClient =  MyHelper::postLoginClient();

                if (isset($loginClient['access_token'])) {
                    session(['access_token'  => 'Bearer ' . $loginClient['access_token']
                    ]);
                }

                return redirect('login')->withErrors($login['messages'])->withInput();
            } else {
                session(['access_token'  => 'Bearer ' . $login['access_token'],
                    'user_name'      => $request->input('username'),
                ]);

                // $userData = MyHelper::get('be/user/');
                $userData = MyHelper::get('be/user/detail');
                if (isset($userData['status']) && $userData['status'] == 'success' && !empty($userData['result'])) {
                    $dataUser = $userData['result'];
                }

                session(['access_token'      => 'Bearer ' . $login['access_token'],
                    'user_id'           => $dataUser['user']['id'],
                    'user_name'         => $dataUser['user']['name'],
                    'user_email'        => $dataUser['user']['email'],
                    'user_role'         => $dataUser['user']['admin_id'],
                    'granted_features'  => $dataUser['features'],
                ]);

                return redirect('home');
            }
        }
        if ($login['status'] == 'success') {
            $user_login = $login['data'];

            if (isset($login['access_token'])) {
                session([
                    'access_token'  => 'Bearer ' . $login['access_token']
                ]);

                $role = MyHelper::get('core-user', 'v1/role/detail/' . $login['data']['role_id']);

                $owned_features = [];
                if (isset($role['status']) && $role['status'] == "success") {
                    foreach ($role['data']['Features'] as $value) {
                        $owned_features[] = $value['id'];
                    }
                }

                session(['granted_features'  => $owned_features
                ]);

                $bearer_token = session('access_token');
                $decoded_token = MyHelper::extractToken($bearer_token);
                $payload_logger = [
                    "user_id" => $decoded_token['id'],
                    "user_email" => $decoded_token['email'],
                    "path" => $request->getPathInfo(),
                    "action" => "CMS Login",
                    "ip_address" => $request->ip(),
                    "user_agent" => $request->header('user-agent')
                ];
                MyHelper::post('core-user', 'v1/cms-activity-log', $payload_logger);

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
