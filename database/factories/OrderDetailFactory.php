<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
            'food_item_id' => FoodItem::inRandomOrder()->first()?->id ?? FoodItem::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'price' => fake()->randomFloat(0, 2, 200) * 1000,
            'status' => fake()->randomElement(['chuẩn bị', 'đã nấu', 'đã ra']),
        ];
    }
}
