<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $table = 'tbl_product_details';

    public static function saveFullProduct($data, $files)
    {
        return DB::transaction(function () use ($data, $files) {
            $productId = DB::table('tbl_product_details')->insertGetId([
                'user_id' => Auth::id(),
                'product_name' => $data['product_name'],
                'product_description' => $data['product_description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (isset($data['brand_name'])) {
                foreach ($data['brand_name'] as $key => $name) {
                    $imagePath = null;
                    
                   if (isset($files['brand_image'][$key])) {
                        $image = $files['brand_image'][$key];
                        $imageName = time() . '_brand_' . $key . '.' . $image->getClientOriginalExtension();
                        $image->move(base_path('brand_images'), $imageName);                    
                        $imagePath = 'brand_images/' . $imageName;
                    }

                    DB::table('tbl_brand_details')->insert([
                        'product_id' => $productId,
                        'brand_name' => $name,
                        'price'      => $data['price'][$key],
                        'detail'     => $data['detail'][$key],
                        'brand_image'=> $imagePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            return true;
        });
    }

    public static function getProductListForUser()
    {
        return DB::table('tbl_product_details')
            ->leftJoin('tbl_brand_details', 'tbl_product_details.id', '=', 'tbl_brand_details.product_id')
            ->where('tbl_product_details.user_id', Auth::id())
            ->where('tbl_product_details.delete_status', '0') 
            ->select([
                'tbl_product_details.id', 
                'tbl_product_details.product_name', 
                'tbl_product_details.product_description', 
                'tbl_product_details.created_at',
                DB::raw('MAX(tbl_brand_details.brand_image) as product_image'),
                DB::raw('COUNT(tbl_brand_details.id) as brands_count')
            ])
            ->groupBy(
                'tbl_product_details.id', 
                'tbl_product_details.product_name', 
                'tbl_product_details.product_description', 
                'tbl_product_details.created_at'
            )
            ->orderBy('tbl_product_details.id', 'desc')
            ->get();
    }

    public static function getBrandsByProductId($id)
    {
        return DB::table('tbl_brand_details')
            ->join('tbl_product_details', 'tbl_brand_details.product_id', '=', 'tbl_product_details.id')
            ->where('tbl_brand_details.product_id', $id)
            ->where('tbl_product_details.user_id', Auth::id()) 
            ->where('tbl_product_details.delete_status', '0') 
            ->select('tbl_brand_details.*')
            ->get();
    }
    public static function deleteUserProduct($id)
    {
        return DB::transaction(function () use ($id) {
            $exists = DB::table('tbl_product_details')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($exists) {
                
                DB::table('tbl_product_details')
                    ->where('id', $id)
                    ->update(['delete_status' => '1']);

                return true;
            }
            return false;
        });
    }
}