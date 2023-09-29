<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartnerEqual;
use App\Models\PartnerSosialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiPartnerEqualController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $partner = $request->length ?  PartnerEqual::paginate($request->length ?? 10) : PartnerEqual::get();
        return $this->ok("Success Get Data All partner", $partner);
    }

    public function show($id): JsonResponse
    {
        $partner_equal = PartnerEqual::with('city.province')->find($id);

        if ($partner_equal) {
            $store = $partner_equal->partner_store()->first();
            if ($store) {
                $sosial_media = $store->partner_sosial_media()->get();
            }
            $result = [
                'partner' => $partner_equal,
                'store' => $store,
                'sosial_media' => $sosial_media ?? null
            ];
            return $this->ok("Success", $result);
        } else {
            return $this->error("Partner not found");
        }
    }


    public function store(Request $request)//: JsonResponse
    {
        $partner_payload = [
            'equal_id' => 0,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
            'city_code' => $request->city_code
        ];
        if ($request->images) {
            $partner_payload['images'] = $request->images;
        }
        $partner = PartnerEqual::create($partner_payload);
        $partner_store_payload = [
            'equal_id' => 0,
            'store_name' => $request->store_name,
            'store_address' => $request->store_address,
            'store_city' => $request->store_city
        ];
        $partner_store = $partner->partner_store()->create($partner_store_payload);
        if ($request->url_instagram) {
            $payload_ig = [
                'equal_id' => 0,
                'type' => 'Instagram',
                'username' => $request->username_instagram,
                'url' => $request->url_instagram
            ];
            $partner_sosial_media_1 = $partner_store->partner_sosial_media()->create($payload_ig);
        }
        if ($request->url_tiktok) {
            $payload_tiktok = [
                'equal_id' => 0,
                'type' => 'Tiktok',
                'username' => $request->username_tiktok,
                'url' => $request->url_tiktok
            ];
            $partner_sosial_media_2 = $partner_store->partner_sosial_media()->create($payload_tiktok);
        }
        if ($request->url_tokopedia) {
            $payload_tokopedia = [
                'equal_id' => 0,
                'type' => 'Tokopedia',
                'username' => $request->username_tokopedia,
                'url' => $request->url_tokopedia
            ];
            $partner_sosial_media_3 = $partner_store->partner_sosial_media()->create($payload_tokopedia);
        }
        if ($request->url_shopee) {
            $payload_shopee = [
                'equal_id' => 0,
                'type' => 'Shopee',
                'username' => $request->username_shopee,
                'url' => $request->url_shopee
            ];
            $partner_sosial_media_4 = $partner_store->partner_sosial_media()->create($payload_shopee);
        }
        if ($request->url_buka_lapak) {
            $payload_bk = [
                'equal_id' => 0,
                'type' => 'Bukalapak',
                'username' => $request->username_buka_lapak,
                'url' => $request->url_buka_lapak
            ];
            $partner_sosial_media_5 = $partner_store->partner_sosial_media()->create($payload_bk);
        }
        return $this->ok("Success", "");
    }

    public function update(Request $request, $id): JsonResponse
    {
        $partner_payload = [
            'equal_id' => 0,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
            'city_code' => $request->city_code
        ];
        if ($request->images) {
            $partner_payload['images'] = $request->images;
        }
        $partner_update = PartnerEqual::find($id)->update($partner_payload);
        $partner = PartnerEqual::find($id);
        $partner_store_payload = [
            'equal_id' => 0,
            'store_name' => $request->store_name,
            'store_address' => $request->store_address,
            'store_city' => $request->store_city
        ];
        $partner_store = $partner->partner_store()->updateOrCreate($partner_store_payload);
        if ($request->url_instagram) {
            $payload_ig = [
                'equal_id' => 0,
                'type' => 'Instagram',
                'username' => $request->username_instagram,
                'url' => $request->url_instagram
            ];
            $partner_sosial_media_1 = $partner_store->partner_sosial_media()->updateOrCreate($payload_ig);
        }
        if ($request->url_tiktok) {
            $payload_tiktok = [
                'equal_id' => 0,
                'type' => 'Tiktok',
                'username' => $request->username_tiktok,
                'url' => $request->url_tiktok
            ];
            $partner_sosial_media_2 = $partner_store->partner_sosial_media()->updateOrCreate($payload_tiktok);
        }
        if ($request->url_tokopedia) {
            $payload_tokopedia = [
                'equal_id' => 0,
                'type' => 'Tokopedia',
                'username' => $request->username_tokopedia,
                'url' => $request->url_tokopedia
            ];
            $partner_sosial_media_3 = $partner_store->partner_sosial_media()->updateOrCreate($payload_tokopedia);
        }
        if ($request->url_shopee) {
            $payload_shopee = [
                'equal_id' => 0,
                'type' => 'Shopee',
                'username' => $request->username_shopee,
                'url' => $request->url_shopee
            ];
            $partner_sosial_media_4 = $partner_store->partner_sosial_media()->updateOrCreate($payload_shopee);
        }
        if ($request->url_buka_lapak) {
            $payload_bk = [
                'equal_id' => 0,
                'type' => 'Bukalapak',
                'username' => $request->username_buka_lapak,
                'url' => $request->url_buka_lapak
            ];
            $partner_sosial_media_5 = $partner_store->partner_sosial_media()->updateOrCreate($payload_bk);
        }
        return $this->ok("Success", $partner);
    }

    public function destroy($id)//: JsonResponse
    {
        $partner_equal = PartnerEqual::find($id);
        if ($partner_equal) {
            $partner_store = $partner_equal->partner_store;
            if ($partner_store) {
                $partner_sosial_media = $partner_store->partner_sosial_media;

                foreach ($partner_sosial_media as $social_media) {
                    $partner_sosial_media_delete = PartnerSosialMedia::find($social_media->id)->delete();
                }
                $partner_store->delete();
            }
            $partner_equal->delete();
        }
        return $this->ok("Success", "");
    }
}
