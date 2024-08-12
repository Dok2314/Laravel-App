<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\DTO\UserDTO;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
      private readonly UserService $userService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        if (!is_numeric($perPage) || $perPage <= 0) {
            return response()->json(['message' => __('validation_messages.per_page')], 400);
        }

        $users = User::paginate((int)$perPage);

        return response()->json($users);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $userDTO = UserDTO::fromArray($validatedData);

        $user = $this->userService->createUser($userDTO);

        return response()->json(['message' => __('user_messages.user_created'), 'user' => $user], 201);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $userDTO = UserDTO::fromArray($validatedData);

            $user = $this->userService->updateUser($id, $userDTO);

            return response()->json(['message' => __('user_messages.user_updated'), 'user' => $user]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json(['message' => __('user_messages.user_deleted')]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function assignAdmin($id)
    {
        try {
            return $this->userService->assignAdminRole($id);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
}
