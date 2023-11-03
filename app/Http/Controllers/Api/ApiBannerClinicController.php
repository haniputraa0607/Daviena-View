<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BannerClinic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiBannerClinicController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BannerClinic::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function ($row) {
                $imageUrl = $row->image;
                if (strpos($imageUrl, 'http:') !== 0 && strpos($imageUrl, 'https:') !== 0) {
                    $imageUrl = env('API_URL') . $imageUrl;
                }
                return '<img src="' . $imageUrl . '" width="200" onerror="imgError(this)">';
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-sm btn-info" data-data=\'' . json_encode($row) . '\' onclick="main.edit(this)">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';
            })
            ->rawColumns(['image','action'])->make(true);
    }

    public function store(BannerClinic $bannerClinic, Request $request): JsonResponse
    {
        return $this->ok("success", $bannerClinic->create($request->all()));
    }
    public function update(BannerClinic $bannerClinic, Request $request): JsonResponse
    {
        $bannerClinic->update($request->all());
        return $this->ok("succes", $bannerClinic);
    }
    public function destroy(BannerClinic $bannerClinic): JsonResponse
    {
        $bannerClinic->delete();
        return $this->ok("succes", $bannerClinic);
    }
}
