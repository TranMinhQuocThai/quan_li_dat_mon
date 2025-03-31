<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->state([
            'name' => 'Administator',
            'email' => 'admin@mail.com',
            'role' => 'admin',
            'password' => Hash::make('1234'),
        ])->create();
        User::factory()->state([
            'name' => 'Staff 01',
            'email' => 'staff01@mail.com',
            'role' => 'staff',
            'password' => Hash::make('1234'),
        ])->create();
        User::factory(12)->state(['role' => 'staff'])->create();
        // User::factory()->state([
        //     'name' => 'Khách vãng lai',
        //     'email' => 'khachvanglai@mail.com',
        //     'role' => 'user',
        //     'password' => Hash::make('1234'),
        // ])->create();
        User::factory()->state([
            'name' => 'User 01',
            'email' => 'user01@mail.com',
            'role' => 'user',
            'password' => Hash::make('1234'),
        ])->create();
        User::factory(12)->create();
    }
}
