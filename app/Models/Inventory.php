<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    protected $table = 'tbl_product_details';

    public static function getTotalStock()
    {
        return DB::table('tbl_product_details')
            ->where('delete_status', '0')
            ->count();
    }
}