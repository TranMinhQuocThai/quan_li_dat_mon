<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Models\OnlineOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineOrderItem>
 */
class OnlineOrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => OnlineOrder::inRandomOrder()->first()?->id,
            'food_item_id' => FoodItem::inRandomOrder()->first()?->id,
            'quantity' => rand(1, 5),
            'price' => fake()->randomFloat(0, 2, 200) * 1000,
            'created_at' => now(),
        ];
    }
}
