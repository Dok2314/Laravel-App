<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserService
{
    public function createUser(UserDTO $userDTO, $roleName = 'client'): User
    {
        $user = User::create($userDTO->toArray());

        $clientRole = Role::where('name', $roleName)->first();

        if ($clientRole) {
            $user->roles()->attach($clientRole->id);
        } else {
            throw new Exception(__('role_messages.client_not_found'));
        }

        return $user;
    }

    public function updateUser(int $id, UserDTO $userDTO): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new Exception(__('user_messages.user_not_found'));
        }

        $user->name = $userDTO->getName();
        $user->email = $userDTO->getEmail();
        if (!empty($userDTO->getPassword())) {
            $user->password = Hash::make($userDTO->getPassword());
        }
        $user->save();

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = User::find($id);

        if (!$user) {
            throw new Exception(__('user_messages.user_not_found'));
        }

        $user->delete();
    }

    public function assignAdminRole(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => __('user_messages.user_not_found')], 404);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
            return response()->json(['message' => __('role_messages.role_updated')]);
        }

        return response()->json(['message' => __('role_messages.role_admin_not_found')], 404);
    }
}
