<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::now()->subDay();
        $totalDays = $startDate->diffInDays($endDate);
        // $quantity = 750;
        for ($i = 1; $i <= $totalDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);

            $orderCount = rand(1, 3);
            for ($j = 0; $j < $orderCount; $j++) {
                $isUnregisteredGuest = rand(1, 100) <= 20;
                Order::factory()->state([
                    'user_id' => $isUnregisteredGuest ? null
                        : User::where('role', '=', 'user')
                            ->inRandomOrder()->first()?->id,
                    'created_at' => $currentDate,
                    'paid' => true,
                ])
                    ->has(
                        OrderDetail::factory()
                            ->state(['status' => 'đã ra', 'created_at' => $currentDate,])
                            ->count(rand(3, 5))
                    )
                    ->create();
            }
        }
        $date = Carbon::now()->subDay();
        $tableIds = Table::pluck('id')->shuffle();
        foreach ($tableIds as $idx => $tableId) {
            $isUnregisteredGuest = rand(1, 100) <= 20;
            $paid = rand(1, 100) <= 30;
            if (!$paid) {
                Table::where('id', $tableId)->update([
                    'status' => 'có khách',
                ]);
            }
            $orderDetailState = $paid ? ['status' => 'đã ra'] : [];
            Order::factory()->state([
                'table_id' => $tableId,
                'user_id' => $isUnregisteredGuest ? null
                    : User::where('role', '=', 'user')
                        ->inRandomOrder()->first()?->id ?? User::factory(),
                'created_at' => $date->addMinute(),
                'paid' => $paid,
            ])
                ->has(
                    OrderDetail::factory()
                        ->state($orderDetailState)
                        ->count(rand(1, 3))
                )
                ->create();
        }
    }
}
