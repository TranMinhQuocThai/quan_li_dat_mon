<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'food_item_id',
        'price',
        'quantity',
    ];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class, 'food_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
  