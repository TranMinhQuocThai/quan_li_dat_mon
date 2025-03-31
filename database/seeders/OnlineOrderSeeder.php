<?php

namespace Database\Seeders;

use App\Models\OnlineOrder;
use App\Models\OnlineOrderItem;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OnlineOrderSeeder extends Seeder
{
    public function run()
    {
        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::now()->subDay();
        $totalDays = $startDate->diffInDays($endDate);

        for ($i = 0; $i <= $totalDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $orderCount = rand(1, 3);
            for ($j = 0; $j < $orderCount; $j++) {
                $onlineOrder = OnlineOrder::factory()->state([
                    'created_at' => $currentDate,
                    'paid' => true,
                    'status' => 'đã giao',
                    'reason' => null,
                ])->create();
                OnlineOrderItem::factory()
                    ->count(rand(3, 5))
                    ->state(['order_id' => $onlineOrder->id, 'created_at' => $currentDate])
                    ->create();
            }
        }

        $date = Carbon::now()->subDay();
        for ($i = 0; $i < 10; $i++) {
            $onlineOrder = OnlineOrder::factory()->state([
                'created_at' => $date->addMinute(),
                'paid' => rand(0, 1),
                'status' => collect(['chờ xác nhận', 'đã xác nhận', 'không nhận', 'đã giao', 'đã hủy'])->random(),
            ])->create();

            OnlineOrderItem::factory()
                ->count(rand(3, 5))
                ->state(['order_id' => $onlineOrder->id, 'created_at' => $currentDate])
                ->create();
        }
    }
}