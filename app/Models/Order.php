<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'discount',
        'paid',
        // 'status',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getTotalAttribute()
    {
        return $this->orderDetails()
            // ->where('status', '!=', 'chuẩn bị')
            ->sum(\DB::raw('quantity * price'));
    }

    public function getBillAttribute()
    {
        $groupedItems = $this->orderDetails
            ->groupBy('food_item_id')
            ->map(function ($items) {
                $firstItem = $items->first();
                $totalQuantity = $items->sum('quantity');
                $subtotal = $totalQuantity * $firstItem->price;

                return [
                    'food_item' => $firstItem->foodItem->name,
                    'quantity' => $totalQuantity,
                    'price' => $firstItem->price,
                    'subtotal' => $subtotal,
                ];
            })
            ->values(); // Reset array indexes

        $totalPrice = $this->total;
        $discountedPrice = $totalPrice / 100 * (100 - $this->discount);

        return [
            'total_price' => $totalPrice,
            'discount' => $this->discount,
            'final_amount' => $discountedPrice,
            'items' => $groupedItems
        ];
    }

}
