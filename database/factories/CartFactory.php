<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\User;
use App\Models\FoodItem;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'food_item_id' => FoodItem::inRandomOrder()->first()->id ?? FoodItem::factory(),
            'price' => $this->faker->numberBetween(10000, 100000),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
