<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $table = 'tbl_product_details';

    protected $fillable = [
        'user_id',
        'product_name',
        'product_description',
        'delete_status',
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}