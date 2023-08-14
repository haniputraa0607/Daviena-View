<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Create;
use App\Http\Requests\User\Update;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ApiUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('city', function ($row) {
                return $row->district->name;
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('user.detail', ['id' => $row->id]) . '">
                            <li class="fa fa-search" aria-hidden="true"></li>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <li class="fa fa-trash" aria-hidden="true"></li>
                        </a>';
            })
            ->rawColumns(['action'])->make(true);
    }
    public function show(User $user): JsonResponse
    {
        return $this->ok("succes", $user);
    }
    public function store(Create $request): JsonResponse
    {
        $user = User::create($request->all());
        return $this->ok("succes", $user);
    }
    public function update(Update $request, User $user): JsonResponse
    {
        $user->update($request->all());
        return $this->ok("succes", $user);
    }
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return $this->ok("succes", $user);
    }

    public function detailUser(Request $request): JsonResponse
    {
        $data['user'] = Auth::user();
        $data['features'] = Auth::user()->get_features();

        return $this->ok(
            "success get data user",
            $data
        );
    }
}
