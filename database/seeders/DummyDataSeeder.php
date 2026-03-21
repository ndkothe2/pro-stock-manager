<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Brand;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $sellers = User::where('role', 'seller')->get();
        
        if ($sellers->isEmpty()) {
            // Create a fallback seller to ensure data is generated
            $seller = User::updateOrCreate(
                ['email' => 'merchant@prostock.com'],
                [
                    'name' => 'Demo Merchant',
                    'mobile_no' => '9876543210',
                    'country' => 'India',
                    'state' => 'Maharashtra',
                    'skills' => json_encode(['Inventory', 'Supplies']),
                    'password' => bcrypt('password123'),
                    'role' => 'seller',
                    'status' => '0'
                ]
            );
            $sellers = collect([$seller]);
        }

        $categories = [
            'Electronics' => ['Smartphones', 'Laptops', 'Headphones', 'Cameras'],
            'Home Appliances' => ['Refrigerators', 'Washing Machines', 'Microwaves', 'Air Conditioners'],
            'Fashion' => ['T-Shirts', 'Jeans', 'Shoes', 'Watches'],
            'Health & Beauty' => ['Skincare', 'Makeup', 'Vitamins', 'Fitness Equipment']
        ];

        foreach ($sellers as $seller) {
            foreach ($categories as $catName => $products) {
                foreach ($products as $prodName) {
                    $product = Product::create([
                        'user_id' => $seller->id,
                        'product_name' => $prodName,
                        'product_description' => "High-quality $prodName from $catName category.",
                        'delete_status' => '0'
                    ]);

                    // Add 2-3 brands per product
                    for ($i = 1; $i <= rand(2, 3); $i++) {
                        Brand::create([
                            'product_id' => $product->id,
                            'brand_name' => "Brand " . chr(64 + $i) . " " . rand(100, 999),
                            'price' => rand(500, 50000),
                            'detail' => "Premium features and reliable performance.",
                            'brand_image' => 'brand_images/placeholder.png' // Assuming this exists or can be fallback
                        ]);
                    }
                }
            }
        }
    }
}
