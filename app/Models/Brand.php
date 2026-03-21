<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Brand extends Model
{
    protected $table = 'tbl_brand_details';

    protected $fillable = [
        'product_id',
        'brand_name',
        'price',
        'detail',
        'brand_image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}