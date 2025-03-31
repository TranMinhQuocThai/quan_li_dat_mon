<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodIngredient extends Pivot
{
    use HasFactory;
    protected $table = 'food_ingredients';
    protected $fillable = ['food_item_id', 'ingredient_id', 'quantity'];
    
    public function food()
    {
        return $this->belongsTo(FoodItem::class, 'food_item_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
