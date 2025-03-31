<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;
use App\Models\FoodItem;

class CartSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $foodItems = FoodItem::all();

        foreach ($users as $user) {
            $randomItems = $foodItems->random(rand(3, 5)); // Mỗi user có 3-5 món

            foreach ($randomItems as $foodItem) {
                Cart::create([
                    'user_id' => $user->id,
                    'food_item_id' => $foodItem->id,
                    'price' => $foodItem->price,
                    'quantity' => rand(1, 3),
                ]);
            }
        }
    }
}
