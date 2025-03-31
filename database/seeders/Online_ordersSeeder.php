<?php

namespace Database\Seeders;

use App\Models\OnlineOrder;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Online_ordersSeeder extends Seeder{
    public function run()
    {
        $data = [];
        $now = Carbon::now();

        for ($i = 1; $i <= 300; $i++) {
            $randomDate = Carbon::create(2024, 6, 1)->addDays(rand(0, $now->diffInDays(Carbon::create(2024, 6, 1))));
            
            $data[] = [
                'user_id' => rand(1, 27),
                'phone' => '09876543' . rand(10, 99),
                'address' => 'Địa chỉ mẫu ' . $i,
                'status' => rand(0, 1),
                'li_do' => 'Lý do đơn hàng ' . $i,
                'da_thanh_toan' => $randomDate->isToday() ? 0 : 1, // Chỉ đơn hôm nay chưa thanh toán
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        DB::table('online_orders')->insert($data);
    }
}