<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_admin_details'; 

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'country',
        'state',
        'skills',
        'password',
        'role',
        'status',
        'delete_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}