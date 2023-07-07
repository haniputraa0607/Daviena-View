<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\LoginRequest;
use App\Lib\MyHelper;
use GoogleReCaptchaV3;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function login(LoginRequest $request)
    {
        $captcha = GoogleReCaptchaV3::verifyResponse($request->input('g-recaptcha-response'))->isSuccess();
        if (!$captcha) {
            return redirect()->back()->withErrors(['Recaptcha failed']);
        }

        $login = MyHelper::login($request);
        if ($login['status'] == 'success') {
            $user_login = $login['data'];

            session([
                'access_token'      => 'Bearer ' . $user_login['token'],
                'user_id'           => $user_login['id'],
                'user_name'         => $user_login['name'],
                'user_email'        => $user_login['email'],
                'user_role'         => $user_login['role'],
            ]);

            $role = MyHelper::get('core-user','v1/role/detail/'.$login['data']['role_id']);

            $owned_features = [];
            if (isset($role['status']) && $role['status'] == "success") {
                foreach ($role['data']['Features'] as $value) {
                    $owned_features[] = $value['id'];
                }
            }

            session([
                'granted_features'  => $owned_features
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
    }

    public function getHome()
    {
        $data = [ 'title'             => 'Home',
                  'menu_active'       => 'home',
                  'submenu_active'    => ''
                ];
        return view('home', $data);
    }
}
