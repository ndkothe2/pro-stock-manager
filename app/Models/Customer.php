<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'tbl_customer_details';

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'mobile_no',
        'country',
        'state',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
