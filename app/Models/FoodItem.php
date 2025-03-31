<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'food_type_id', 'price', 'description'];

    public function foodType()
    {
        return $this->belongsTo(FoodType::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'food_ingredients')
            ->withPivot(['id', 'quantity'])
            ->withTimestamps();
    }

}
