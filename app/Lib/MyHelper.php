<?php

namespace App\Lib;

use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use JWTAuth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MyHelper
{
    public static function postLogin($request)
    {
        $api = env('APP_API_URL');
        $form_params =  [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => env('PASSWORD_CREDENTIAL_ID'),
                'client_secret' => env('PASSWORD_CREDENTIAL_SECRET'),
                'username'      => $request->input('username'),
                'password'      => $request->input('password'),
                'scope'         => 'be'
            ],
        ];

        $client = new Client();
        try {
            $response = $client->request('POST', $api . 'oauth/token', $form_params);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function postLoginClient()
    {
        $api = env('APP_API_URL');
        $client = new Client();

        try {
            $response = $client->request('POST', $api . 'oauth/token', [
                'form_params' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => env('CLIENT_CREDENTIAL_ID'),
                    'client_secret' => env('CLIENT_CREDENTIAL_SECRET'),
                    'scope'             => 'be'
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function post($url, $post)
    {
        $host = env('APP_API_URL_CMS');

        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
            'json'      => (array) $post

        );

        try {
            $response = $client->post($host . 'api/' . $url, $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    if (!is_array($response)) {
                    }
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function patch($url, $post)
    {
        $host = env('APP_API_URL_CMS');

        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
            'json'      => (array) $post

        );
        try {
            $response = $client->patch($host . 'api/' . $url, $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    if (!is_array($response)) {
                    }
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function get($url)
    {

        // $host = env('APP_API_URL_CMS');
        $host = env('APP_API_URL');

        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
        );
        try {
            $response =  $client->request('GET', $host . 'api/' . $url, $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    $error = json_decode($response, true);

                    if (!$error) {
                        return $e->getResponse()->getBody();
                    } else {
                        return $error;
                    }
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function postCurl($url, $data = '')
    {
        $host = env('APP_API_URL_CMS');
        // $host = env('APP_API_URL');

        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
        );
        try {
            $response =  $client->request('POST', $host . 'api/' . $url, [
                'headers' => $content,
                'json' => ($data),
            ]);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    $error = json_decode($response, true);

                    if (!$error) {
                        return $e->getResponse()->getBody();
                    } else {
                        return $error;
                    }
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function deleteApi($url, $post = null)
    {
        $api = env('APP_API_URL_CMS');
        $client = new Client();
        $ses = session('access_token');
        $content = array(
            'headers' => [
                'Authorization'   => $ses,
            ],
            'connect_timeout' => 15,
        );
        try {
            $response =  $client->request('DELETE', $api . 'api/' . $url, $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    $error = json_decode($response, true);
                    if (!$error) {
                        Log::debug([
                            'url' => $api . '/' . $url,
                            'param'  => $content,
                            'response'  => $e
                        ]);
                        return;
                    } else {
                        Log::debug([
                            'url' => $api . '/' . $url,
                            'param'  => $content,
                            'response'  => $error
                        ]);
                        return $error;
                    }
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (\Exception $e) {
                Log::debug([
                    'url' => $api . '/' . $url,
                    'param'  => $content,
                    'response'  => $e
                ]);
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function renderMenu($allMenu = null, &$hasActive = false, &$hasChildren = false)
    {
        $menuHtml = '';
        $hasActive = false;
        $hasChildren = false;
        if (is_null($allMenu)) {
            $allMenu = config('menu.sidebar');
        }

        foreach ($allMenu as $menu) {
            if (session('user_role') != 'super_admin') {
                if ($menu['required_features'] ?? false) {
                    if (!MyHelper::hasAccess($menu['required_features'], session('granted_features'))) {
                        continue;
                    }
                }
            }

             $url = $menu['url'] ?? '';
            if (substr($url, 0, 4) == 'http') {
                $url = $menu['url'];
            } else {
                $url ??= '';
                $url = $url ? url($url) : 'javascript:void(0)';
            }
            // $url = substr($menu['url'] ?? '', 0, 4) == 'http' ? $menu['url'] : ($menu['url'] ?? '') ? url($menu['url']) : 'javascript:void(0)'; //php v7
            $icon = ($menu['icon'] ?? '') ? '<i class="' . $menu['icon'] . '"></i>' : '';

            switch ($menu['type'] ?? 'single') {
                case 'tree':
                    $submenu = '<li class="nav-item %active%"><a href="' . $url . '" class="nav-link nav-toggle">' . $icon . '<span class="title">' . $menu['label'] . '</span><span class="arrow %active%"></span></a><ul class="sub-menu">';

                    $submenu .= static::renderMenu($menu['children'], $subActive, $subAvailable);

                    $submenu = str_replace('%active%', $subActive ? 'active open' : '', $submenu);

                    $submenu .= '</ul></li>';

                    if ($subAvailable) {
                        if ($subActive) {
                            $hasActive = true;
                        }
                        $menuHtml .= $submenu;
                    }
                    break;

                case 'group':
                    $submenu = '';
                    if ($menu['label'] ?? false) {
                        $submenu .= static::renderMenu([['type' => 'heading', 'label' => $menu['label']]], $subActive, $subAvailable);
                    }
                    $submenu .= static::renderMenu($menu['children'] ?? [], $subActive, $subAvailable);
                    if ($subAvailable) {
                        if ($subActive) {
                            $hasActive = true;
                        }
                        $menuHtml .= $submenu;
                    }
                    break;

                case 'heading':
                    $menuHtml .= '<li class="heading" style="height: 50px;padding: 25px 15px 10px;"><h3 class="uppercase" style="color: #000;font-weight: 600;">' . $menu['label'] . '</h3></li>';
                    break;

                default:
                    if (!($menu['active'] ?? false)) {
                        $menu['active'] = 'request()->path() == ($menu["url"]??"")';
                    }
                    $active = ($menu['active'] ?? false) ? (eval('return ' . $menu['active'] . ';') ? 'active open' : '') : '';
                    if ($active) {
                        $hasActive = true;
                    }
                    $menuHtml .= '<li class="nav-item ' . $active . '"><a href=" ' . $url . ' " class="nav-link">' . $icon . '<span class="title">' . ($menu['label'] ?? 'YAYAYA') . '</span></a></li>';
                    break;
            }
        }
        if ($menuHtml) {
            $hasChildren = true;
        }
        return $menuHtml;
    }

    public static function hasAccess($granted, $features)
    {
        foreach ($granted as $g) {
            if (!is_array($features)) {
                $features = session('granted_features');
            }
            if (in_array($g, $features)) {
                return true;
            }
        }

        return false;
    }

    public static function uploadFile($file, $root_path, $path, $filename = "")
    {
        try {
            try {
                $ext = $file->getClientOriginalExtension();
                if ($filename == "") {
                    $filename = $file->getClientOriginalName();
                } else {
                    $filename = $filename . '.' . $ext;
                }

                $full_path = $root_path . "/" . $path . '/' . $filename;

                $save = Storage::disk(config('filesystems.default'))->put($full_path, file_get_contents($file), 'public');
                // $save = Storage::disk(config('filesystems.default'))->put($full_path, fread(fopen($file, "r"), filesize($file)), 'public'); // video cannot upload
                if ($save) {
                    return [
                        "path" => $full_path,
                        "file_name" => $filename
                    ];
                } else {
                    throw new Exception('Failed to store file!');
                }
            } catch (Exception $error) {
                throw new Exception($error->getMessage());
            }
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    public static function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time());

        return $filename;
    }

    public static function deleteImageNotExist($path, $image_list = [])
    {
        if (!empty($image_list)) {
            // Get all files in the folder
            $files = Storage::files($path);
            // Loop through each file in the folder
            foreach ($files as $file) {
                // If the file is not in the array of filenames, delete it
                if (!in_array($file, $image_list)) {
                    Storage::delete($file);
                }
            }
        }
    }

    public static function extractToken($bearer_token)
    {
        $access_token = str_replace('Bearer ', '', $bearer_token);
        $token = new \Tymon\JWTAuth\Token($access_token);
        $decoded_token = JWTAuth::decode($token);

        return $decoded_token;
    }

    public static function GenerateQR($value, $filename, $output_path, $additional_text = null)
    {
        $logo = public_path('images/logo/casion-face.png');

        $image = QrCode::format('png')
                 ->merge($logo, 0.2, true)
                 ->size(1080)->errorCorrection('H')
                 ->margin($additional_text != null ? 8 : 2)
                 ->generate($value);
        $output_file = $output_path . '/' . $filename . '.png';

        if ($additional_text == null) {
            Storage::disk(config('filesystems.default'))->put($output_file, $image);
        } else {
            $temp_qr = $output_path . '/temp_' . $filename . '-' . time() . '.png';
            Storage::disk(config('filesystems.default'))->put($temp_qr, $image);

            $img = Image::make(storage_path("app/public/" . $temp_qr));
            $img->text(
                $additional_text,
                $img->getWidth() / 2,
                $img->getHeight() - ($img->getHeight() / 15),
                function ($font) use ($img) {
                    $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
                    $font->size($img->getWidth() / 20);
                    $font->color([0, 0, 0]);
                    $font->valign('middle');
                    $font->align('center');
                }
            )->save(storage_path("app/public/" . $output_file));

            if (file_exists(storage_path("app/public/" . $temp_qr))) {
                Storage::delete($temp_qr);
            }
        }

        return $output_file;
    }

    //TODO Development only, will remove soon!
    public static function RegisterECS($ecs_id)
    {
        $payload = [
            "ecs_id" => $ecs_id
        ];
        $host = env('APP_API_URL_CORE');

        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
            'json'      => (array) $payload
        );

        try {
            $response = $client->post($host . 'ecs/v1/register', $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    if (!is_array($response)) {
                    }
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function generateReference()
    {
        $encryptedUserID = session('user_id');
        $decrypt = self::get('core-api', 'v1/helper/decrypt?id=' . $encryptedUserID);

        $userId = $decrypt['data'] ?? 0;
        // $paddedUserId = str_pad($userId, 4, '0', STR_PAD_LEFT);

        $currentTime = Carbon::now();
        $formattedDate = $currentTime->format('ymdHi');
        $reference = "CMS-" . $formattedDate;

        return $reference;
    }

    public static function startCharging($post)
    {
        $host = env('APP_API_URL_DEVICE_CENTRAL');
        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
            'json'      => (array) $post

        );

        try {
            $response = $client->post($host . 'api/cms/start-charging', $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    if (!is_array($response)) {
                    }
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function stopCharging($post)
    {
        $host = env('APP_API_URL_DEVICE_CENTRAL');
        $client = new Client();
        $bearer = session('access_token');
        $content = array(
            'headers'   => [
                            'Authorization' => $bearer,
                            'Accept'        => 'application/json',
                            'Content-Type'  => 'application/json',
                        ],
            'json'      => (array) $post

        );

        try {
            $response = $client->post($host . 'api/cms/stop-charging', $content);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            try {
                if ($e->getResponse()) {
                    $response = $e->getResponse()->getBody()->getContents();
                    if (!is_array($response)) {
                    }
                    return json_decode($response, true);
                } else {
                    return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
                }
            } catch (Exception $e) {
                return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
            }
        }
    }

    public static function indonesian_date($timestamp = '', $time_zone = 'Asia/Jakarta', $date_format = 'l, d F Y H:i')
    {
        if (trim($timestamp) == '') {
            $timestamp = time();
        } elseif (!ctype_digit($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        $date = new DateTime("@$timestamp");
        $date->setTimezone(new DateTimeZone($time_zone));

        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace("/S/", "", $date_format);
        $pattern = array(
            '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
            '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
            '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
            '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
            '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
            '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
            '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
            '/November/', '/December/',
        );
        $replace = array(
            'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
            'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'Sepember',
            'Oktober', 'November', 'Desember',
        );

        $date_str = $date->format($date_format);
        $date_str = preg_replace($pattern, $replace, $date_str);

        $time_zone_abbreviation = self::getTimeZoneAbbreviation($time_zone);
        $date_str .= " " . $time_zone_abbreviation;

        return $date_str;
    }

    private static function getTimeZoneAbbreviation($time_zone)
    {
        $time_zone_abbreviations = array(
            'Asia/Jakarta' => 'WIB',
            'Asia/Pontianak' => 'WIB',
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
        );

        return $time_zone_abbreviations[$time_zone] ?? '';
    }

    public static function sortVersions(array $versions)
    {
        // Custom comparison function
        $compareVersions = function ($version1, $version2) {
            $versionComponents1 = explode('.', $version1['app_version']);
            $versionComponents2 = explode('.', $version2['app_version']);

            $length = max(count($versionComponents1), count($versionComponents2));
            for ($i = 0; $i < $length; $i++) {
                $component1 = $versionComponents1[$i] ?? 0;
                $component2 = $versionComponents2[$i] ?? 0;

                if ($component1 != $component2) {
                    return $component2 - $component1;
                }
            }

            return 0;
        };

        // Sort the versions using the custom comparison function
        usort($versions, $compareVersions);

        return $versions;
    }
}
