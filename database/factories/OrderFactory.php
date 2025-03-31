<?php

namespace Database\Factories;

use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'table_id' => Table::inRandomOrder()->first()?->id ?? Table::factory(),
            'paid' => fake()->boolean(30),
            // 'status' => fake()->randomElement(['đang ăn', 'đã ăn', 'đã thanh toán']),
            'discount' => fake()->randomFloat(0, 0, 50),
        ];
    }
}
