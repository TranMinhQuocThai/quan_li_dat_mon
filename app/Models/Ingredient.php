<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'quantity',
        'unit',
    ];
    public $timestamps = true;
    
    public function foodItems()
    {
        return $this->belongsToMany(FoodItem::class, 'food_ingredients')
                    ->withPivot(['id', 'quantity'])
                    ->withTimestamps();
    }
}
