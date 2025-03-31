<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'quantity' => fake()->randomFloat(2, 10, 1000), // Random quantity between 10 and 1000
            'unit' => fake()->randomElement(['g', 'kg', 'ml', 'l']), // Common units
        ];

    }
}
