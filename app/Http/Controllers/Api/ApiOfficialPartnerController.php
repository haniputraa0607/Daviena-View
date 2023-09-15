<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\OfficialPartner;
use App\Models\OfficialPartnerDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiOfficialPartnerController extends Controller
{
    public function index(): JsonResponse
    {
        $official = OfficialPartner::first();
        $detail = OfficialPartnerDetail::all();
        return $this->ok('success', [
            'official' => $official,
            'detail' => $detail
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $official = OfficialPartner::first();
        $official->update([
            'description' => $request->description_official
        ]);
        foreach ($request->detail_id as $key => $value) {
            $payload_detail = [
                'link' => $request->link[$key],
                'description' => $request->description[$key]
            ];
            OfficialPartnerDetail::where('id', $request->detail_id[$key])->update($payload_detail);
        }
        return $this->ok("success", true);
    }
}
