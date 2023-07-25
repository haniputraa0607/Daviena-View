<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    public function index(Request $request) : JsonResponse {
        $data = User::paginate($request->length ?? 15);
        return $this->ok("succes", $data);
    }
    public function show(User $user) : JsonResponse {
        return $this->ok("succes", $user);
    }
    public function store(Request $request) : JsonResponse {
        $user = User::create($request->all());
        return $this->ok("succes", $user);
    }
    public function update(Request $request, User $user) : JsonResponse {
        $user->update($request->all());
        return $this->ok("succes", $user);
    }
    public function destroy(User $user) : JsonResponse {
        $user->delete();
        return $this->ok("succes", $user);
    }
    
}
