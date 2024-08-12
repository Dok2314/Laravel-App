<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            Order::factory()->count(3)->create([
                'user_id' => $user->id,
                'product_id' => $products->random()->id,
            ]);
        }
    }
}
