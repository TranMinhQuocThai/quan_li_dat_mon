<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodIngredient>
 */
class FoodIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'food_item_id' => FoodItem::inRandomOrder()->first()->id ?? FoodItem::factory(),
            'ingredient_id' => Ingredient::inRandomOrder()->first()->id ?? Ingredient::factory(),
            'quantity' => fake()->randomFloat(0, 1, 50),
        ];
    }
}
