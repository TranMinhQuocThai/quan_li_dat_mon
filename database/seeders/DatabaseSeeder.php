<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      UserSeeder::class,
      TableSeeder::class,
      IngredientSeeder::class,
      FoodItemSeeder::class,
      OrderSeeder::class,
      CartSeeder::class,
      OnlineOrderSeeder::class,
    ]);

    // // Seed Departments
    // DB::table('departments')->insert([
    //   ['name' => 'Kitchen'],
    //   ['name' => 'Service'],
    //   ['name' => 'Management'],
    //   ['name' => 'IT'],
    //   ['name' => 'HR'],
    //   ['name' => 'Finance'],
    //   ['name' => 'Marketing'],
    //   ['name' => 'Sales'],
    //   ['name' => 'Support'],
    //   ['name' => 'Operations']
    // ]);

    // // Seed Employees
    // $employees = [
    //   ['John Doe', 'john@example.com', '1234567890', 1],
    //   ['Jane Smith', 'jane@example.com', '0987654321', 2],
    //   ['Michael Johnson', 'michael@example.com', '1122334455', 3],
    //   ['Emily Davis', 'emily@example.com', '2233445566', 4],
    //   ['David Brown', 'david@example.com', '3344556677', 5],
    //   ['Emma Wilson', 'emma@example.com', '4455667788', 6],
    //   ['Olivia Martin', 'olivia@example.com', '5566778899', 7],
    //   ['William Anderson', 'william@example.com', '6677889900', 8],
    //   ['Sophia Thomas', 'sophia@example.com', '7788990011', 9],
    //   ['James White', 'james@example.com', '8899001122', 10],
    // ];
    // foreach ($employees as $idx => $employee) {
    //   DB::table('employees')->insert([[
    //     'name' => $employee[0],
    //     'email' => $employee[1],
    //     'phone' => $employee[2],
    //     'department_id' => $employee[3],
    //   ]]);
    // }

    // // Seed Customers
    // $customers = [
    //   ['Alice Johnson', 'alice@example.com', '5551112222'],
    //   ['Bob Williams', 'bob@example.com', '5553334444'],
    //   ['Charlie Brown', 'charlie@example.com', '5555556666'],
    //   ['Diana King', 'diana@example.com', '5557778888'],
    //   ['Ethan Lee', 'ethan@example.com', '5559990000'],
    //   ['Fiona Harris', 'fiona@example.com', '5551113333'],
    //   ['George Clark', 'george@example.com', '5554446666'],
    //   ['Hannah Lewis', 'hannah@example.com', '5557779999'],
    //   ['Isaac Young', 'isaac@example.com', '5552224444'],
    //   ['Jessica Scott', 'jessica@example.com', '5558880000'],
    // ];
    // foreach ($customers as $customer) {
    //   DB::table('customers')->insert([[
    //     'name' => $customer[0],
    //     'email' => $customer[1],
    //     'phone' => $customer[2]
    //   ]]);
    // }
  }
}
