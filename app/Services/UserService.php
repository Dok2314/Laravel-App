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
    public function createUser(UserDTO $userDTO): User
    {
        $user = User::create($userDTO->toArray());

        $clientRole = Role::where('name', 'client')->first();

        if ($clientRole) {
            $user->roles()->attach($clientRole->id);
        } else {
            throw new Exception('Client role not found');
        }

        return $user;
    }

    public function updateUser(int $id, UserDTO $userDTO): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new Exception('User not found');
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
            throw new Exception('User not found');
        }

        $user->delete();
    }

    public function assignAdminRole(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
            return response()->json(['message' => 'User role updated to admin']);
        }

        return response()->json(['message' => 'Admin role not found'], 404);
    }
}
