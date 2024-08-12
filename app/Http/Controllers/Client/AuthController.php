<?php

namespace App\Http\Controllers\Client;

use App\DTO\UserDTO;
use App\Http\Requests\RegistrationRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();
        $userDTO = UserDTO::fromArray($data);

        $user = $this->userService->createUser($userDTO);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }
}


