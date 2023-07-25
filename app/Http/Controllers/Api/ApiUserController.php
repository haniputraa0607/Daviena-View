<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->length ?  User::display()->paginate($request->length ?? 10) : User::display()->get();
        return $this->ok("success get data all users", $user);
    }
    public function show(User $user): JsonResponse
    {
        return $this->ok("succes", $user);
    }
    public function store(Request $request): JsonResponse
    {
        $user = User::create($request->all());
        return $this->ok("succes", $user);
    }
    public function update(Request $request, User $user): JsonResponse
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
