<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Thịt bò', 'quantity' => 125, 'unit' => 'kg'],
            ['name' => 'Thịt heo', 'quantity' => 100, 'unit' => 'kg'],
            ['name' => 'Thịt gà', 'quantity' => 120, 'unit' => 'kg'],
            ['name' => 'Cua hoàng đế', 'quantity' => 112, 'unit' => 'kg'],
            ['name' => 'Tôm thái hậu', 'quantity' => 113, 'unit' => 'kg'],
            ['name' => 'Cá tra', 'quantity' => 120, 'unit' => 'kg'],
            ['name' => 'Cá trê', 'quantity' => 90, 'unit' => 'kg'],
            ['name' => 'Phô mai', 'quantity' => 160, 'unit' => 'kg'],
            ['name' => 'Hột vịt', 'quantity' => 500, 'unit' => 'cái'],
            ['name' => 'Sữa trâu', 'quantity' => 300, 'unit' => 'l'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
