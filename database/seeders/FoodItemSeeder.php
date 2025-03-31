<?php

namespace Database\Seeders;

use App\Models\FoodIngredient;
use App\Models\FoodItem;
use App\Models\FoodType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foodTypes = [
            'Món nước' => [
                [
                    'name' => 'Lẩu thái',
                    'image' => 'food_items/lau-thai.jpg',
                ],
                [
                    'name' => 'Lẩu tomyum',
                    'image' => 'food_items/lau-tomyum.jpg',
                ],
            ],
            'Món cơm' => [
                [
                    'name' => 'Cơm chiên nước mắm',
                    'image' => 'food_items/com-chien-nuoc-mam.jpg',
                ],
                [
                    'name' => 'Cơm chiên trân châu',
                    'image' => 'food_items/com-chien-tran-chau.jpg',
                ],
            ],
            'Món chay' => [

            ],
            'Hải sản' => [
                [
                    'name' => 'Ghẹ hấp bia',
                    'image' => 'food_items/ghe-hap-bia.jpg',
                ],
                [
                    'name' => 'Mực nướng muối ớt',
                    'image' => 'food_items/muc-nuong-muoi-ot.jpg',
                ],
                [
                    'name' => 'Tôm sú nướng',
                    'image' => 'food_items/tom-su-nuong.jpg',
                ],
                [
                    'name' => 'Ốc hương rang muối',
                    'image' => 'food_items/oc-huong-rang-muoi.jpg',
                ],
            ],
            'Món nướng' => [
                [
                    'name' => 'Ba chỉ nướng sa tế',
                    'image' => 'food_items/ba-chi-nuong-sa-te.jpg',
                ],
                [
                    'name' => 'Bò nướng lá lốt',
                    'image' => 'food_items/bo-nuong-la-lot.jpg',
                ],
                [
                    'name' => 'Khô mực nướng',
                    'image' => 'food_items/kho-muc-nuong.jpg',
                ],
            ],
            'Món hấp' => [
                [
                    'name' => 'Ngao hấp sả',
                    'image' => 'food_items/ngao-hap-sa.jpg',
                ],
                [
                    'name' => 'Bạch tuộc hấp gừng',
                    'image' => 'food_items/bach-tuoc-hap-gung.jpg',
                ],
                [
                    'name' => 'Gà hấp hành',
                    'image' => 'food_items/ga-hap-hanh.jpg',
                ],
            ],
            'Món xào' => [
                [
                    'name' => 'Mì xào hải sản',
                    'image' => 'food_items/mi-xao-hai-san.jpg',
                ],
                [
                    'name' => 'Rau muống xào tỏi',
                    'image' => 'food_items/rau-muong-xao-toi.jpg',
                ],
            ],
            'Món gỏi' => [

            ],
            'Bánh & ăn vặt' => [
                [
                    'name' => 'Đậu hũ chiên giòn',
                    'image' => 'food_items/dau-hu-chien-gion.jpg',
                ],
                [
                    'name' => 'Bò khô lá chanh',
                    'image' => 'food_items/bo-kho-la-chanh.jpg',
                ],
            ],
            'Đồ uống' => [
                [
                    'name' => 'Bia Tiger',
                    'image' => 'food_items/bia-tiger.webp',
                ],
                [
                    'name' => 'Bia Heineken',
                    'image' => 'food_items/heineken.webp',
                ],
                [
                    'name' => 'Rượu nếp than',
                    'image' => 'food_items/ruou-nep-than.jpeg',
                ],
                [
                    'name' => 'Rượu chuối hột',
                    'image' => 'food_items/ruou-chuoi-hot.jpg',
                ],
                [
                    'name' => 'Nước ngọt Coca-Cola',
                    'image' => 'food_items/coca-cola.jpg',
                ],
                [
                    'name' => 'Trà đá',
                    'image' => 'food_items/tra-da.jpg',
                ],
            ],
        ];
        $date = Carbon::create(2024, 1, 1);
        foreach ($foodTypes as $key => $foodItems) {
            $foodType = FoodType::factory()->create([
                'name' => $key,
            ]);
            foreach ($foodItems as $item) {
                $foodItem = FoodItem::factory()->create([
                    'name' => $item['name'],
                    'image' => $item['image'],
                    'food_type_id' => $foodType->id,
                    'created_at' => $date->addDay(),
                ]);
                FoodIngredient::factory(2)->create([
                    'food_item_id' => $foodItem->id,
                ]);
            }
        }
    }
}
