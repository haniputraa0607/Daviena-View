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
            ->editColumn('type', function ($row) {
                return $row->type == 'salesman' ? 'Doctor' : ucfirst($row->type);
            })
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
        $user->district->city->province;
        return $this->ok("succes", $user);
    }
    public function store(Create $request): JsonResponse
    {
        $data = $request->all();
        if (isset($data['commission_fee'])) {
            $data['commission_fee'] = $data['commission_fee'] / 100;
        }
        $user = User::create($data);
        return $this->ok("succes", $user);
    }
    public function update(Update $request, User $user): JsonResponse
    {
        $data = $request->all();
        if (isset($data['commission_fee'])) {
            $data['commission_fee'] = $data['commission_fee'] / 100;
        }
        $user->update($data);
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

    public function getDoctorList(Request $request): JsonResponse
    {
        $data =  User::doctor()
        ->when($request->q, fn($query) => $query->where('name', 'ILIKE', '%' . $request->q . '%')) //For pgsql
        // ->when($request->q, fn($query) => $query->where('name', 'LIKE', '%' . $request->q . '%')->collate('utf8_general_ci')) //For mysql
        ->select('name', 'id')->get();
        return $this->ok("success get doctor list", $data);
    }

    public function getCashierList(): JsonResponse
    {
        $data =  User::cashier()->select('name', 'id')->get();
        return $this->ok("success get doctor list", $data);
    }

    public function nameId(Request $request): JsonResponse
    {
        return $this->ok("succes get outlet", User::select('name', 'id')->get());
    }

    public function generateUsername(Request $request): JsonResponse
    {
        $user =  User::where('type', $request->type)->orderBy('id', 'desc')->latest()->first();

        $first = match ($user->type) {
             'salesman'=> 'Dok',
             'cashier'=> 'Kas',
             'admin'=> 'Adm',
        };

        $second = explode(' ', $user->name)[0];

        $third = (int)str_replace($first . $second, '', $user->username);
        $data = [
            "current" => $third,
            "next" => $third + 1,
        ];
        return $this->ok("success", $data);
    }
}
