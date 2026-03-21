<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@prostock.com'],
            [
                'name' => 'Nikhil Admin',
                'mobile_no' => '1234567890',
                'country' => 'India',
                'state' => 'Maharashtra',
                'skills' => json_encode(['Management', 'Security']),
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'seller@prostock.com'],
            [
                'name' => 'Nikhil Seller',
                'mobile_no' => '0987654321',
                'country' => 'India',
                'state' => 'Maharashtra',
                'skills' => json_encode(['Sales', 'Marketing']),
                'password' => bcrypt('seller123'),
                'role' => 'seller',
            ]
        );
    }
}
