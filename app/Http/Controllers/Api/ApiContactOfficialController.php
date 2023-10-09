<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactOfficial;
use App\Models\ContactSosialMedia;
use App\Models\ProductGlobalPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiContactOfficialController extends Controller
{
    public function index(): JsonResponse
    {
        $contact_official = ContactOfficial::whereIn("official_name", [
            "WhatsApp",
            "Working Hour"
        ])->get();
        $contact_sosial_media = ContactSosialMedia::all();
        return $this->ok('success', [
            'contact_official' => $contact_official,
            'contact_sosial_media' => $contact_sosial_media
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $contact_official_wa = ContactOfficial::find($request->id_whatsapp)->update([
            'contact_official_value' => 'whatsapp'
        ]);
        $contact_official_working = ContactOfficial::find($request->id_working_hour);
        $data_working = [];
        foreach ($request->working_hour as $key) {
            $data_working[] = $key;
        }
        if ($data_working) {
            $contact_official_working->update($data_working);
        }
        for ($i = 0; $i < count($request->detail_id); $i++) {
            ContactSosialMedia::find($request->detail_id[$i])->update([
                'link' => $request->link[$i],
                'username' => $request->username[$i]
            ]);
        }
        return $this->ok("succes", true);
    }

    public function consultationOrdering()
    {
        $contact_official = ContactOfficial::where("official_name", "Consultation & Ordering")->first();
        return $this->ok('success', $contact_official);
    }

    public function consultationOrderingUpdate(Request $request): JsonResponse
    {
        $payload = [
            [
                "contact" => [
                    [
                        "name" => $request->name[0],
                        "telp" => $request->telp[0]
                    ],
                    [
                        "name" => $request->name[1],
                        "telp" => $request->telp[1]
                    ],
                ],
            ],
            [
                "service_hours" => $request->service_hours
            ],
        ];
        $data_update = [
            'official_value' => json_encode($payload)
        ];
        ContactOfficial::find($request->id)->update($data_update);
        return $this->ok("succes", true);
    }
}
