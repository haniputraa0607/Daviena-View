<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfficialPartnerHome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiOfficialPartnerHomeController extends Controller
{
    public function index(OfficialPartnerHome $official_partner_home): JsonResponse
    {
        return $this->ok('success', $official_partner_home->all());
    }

    public function update(Request $request): JsonResponse
    {
        OfficialPartnerHome::truncate();
        foreach ($request->partner as $key) {
            OfficialPartnerHome::create([
                'partner_equal_id' => $key
            ]);
        }
        return $this->ok("succes", true);
    }
}
