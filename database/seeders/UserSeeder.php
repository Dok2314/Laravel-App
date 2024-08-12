<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $clientRole = Role::where('name', 'client')->first();

        $adminUser = User::factory()->create([
            'name' => 'Daniil',
            'email' => 'daniil@gmail.com',
            'password' => bcrypt('Daniil123456'),
        ]);

        $adminUser->roles()->attach($adminRole);

        $this->createClients($clientRole);

        $this->createUsersWithRandomRole($adminRole, $clientRole);
    }

    protected function createClients(Role $clientRole): void
    {
        User::factory()->count(5)->create()->each(function ($user) use ($clientRole) {
            $user->roles()->attach($clientRole);
        });
    }

    protected function createUsersWithRandomRole(Role $adminRole, Role $clientRole) : void
    {
        User::factory()->count(3)->create()->each(function ($user) use ($adminRole, $clientRole) {
            $user->roles()->attach(rand(0, 1) ? $adminRole : $clientRole);
        });
    }
}
