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
        \App\Models\User::create([
            'name' => 'Nikhil Admin',
            'email' => 'admin@prostock.com',
            'mobile_no' => '1234567890',
            'country' => 'India',
            'state' => 'Maharashtra',
            'skills' => json_encode(['Management', 'Security']),
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);
    }
}
