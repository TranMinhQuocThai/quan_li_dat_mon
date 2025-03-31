<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FoodItem;
class OnlineOrderItem extends Model
{
    use HasFactory;

    protected $table = 'online_orders_items';

    protected $fillable = ['order_id', 'food_item_id', 'quantity', 'price'];

    public function food()
    {
        return $this->belongsTo(FoodItem::class, 'food_item_id');
    }
}

