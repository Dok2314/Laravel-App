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

class UserController extends Controller
{
    public function __construct(
      private readonly UserService $userService
    ) {
    }

    public function index()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $userDTO = UserDTO::fromArray($validatedData);

        $user = $this->userService->createUser($userDTO);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $userDTO = UserDTO::fromArray($validatedData);

            $user = $this->userService->updateUser($id, $userDTO);

            return response()->json(['message' => 'User updated successfully', 'user' => $user]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json(['message' => 'User deleted successfully']);
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
