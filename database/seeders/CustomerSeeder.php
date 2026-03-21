<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'mobile_no' => '+91 98765 43210',
                'country' => 'India',
                'state' => 'Maharashtra',
                'password' => Hash::make('customer123'),
                'status' => '0'
            ]
        );
    }
}
