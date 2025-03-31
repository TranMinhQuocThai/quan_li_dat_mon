<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineOrder>
 */
class OnlineOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['chờ xác nhận', 'đã xác nhận', 'không nhận', 'đã giao', 'đã hủy'];

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'phone' => $this->faker->numerify('0#########'),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement($statuses),
            'reason' => $this->faker->boolean(10) ? $this->faker->sentence() : null, // 10% chance of reason
            'paid' => $this->faker->boolean(40), // 40% chance order is paid
        ];
    }
}
