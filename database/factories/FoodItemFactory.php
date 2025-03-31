<?php

namespace Database\Factories;

use App\Models\FoodType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodItem>
 */
class FoodItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'image' => fake()->word(),
            'food_type_id' => FoodType::inRandomOrder()->first()?->id ?? FoodType::factory(),
            'price' => fake()->randomFloat(0, 2, 200) * 1000,
            'description' => fake()->sentence(),
        ];
    }
}
