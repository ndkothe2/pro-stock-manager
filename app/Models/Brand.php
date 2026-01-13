<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Brand extends Model
{
    protected $table = 'tbl_brand_details';

    public static function getTotalBrands()
    {
        return DB::table('tbl_brand_details')
            ->join('tbl_product_details', 'tbl_brand_details.product_id', '=', 'tbl_product_details.id')
            ->where('tbl_product_details.delete_status', '0')
            ->count();
    }
}