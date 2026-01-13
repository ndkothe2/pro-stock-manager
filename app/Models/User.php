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
    
    public static function authorizeSellerEntry($data, $skills)
    {
        try {
            return DB::table('tbl_admin_details')->insert([
                'name'       => $data['full_name'],
                'email'      => $data['email'],
                'mobile_no'  => $data['mobile_no'], 
                'country'    => $data['country'],
                'state'      => $data['state'],
                'skills'     => json_encode($skills), 
                'password'   => Hash::make($data['password']),
                'role'       => 'seller', 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error("SELLER_AUTH_ERROR: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllSellers()
    {
        return DB::table('tbl_admin_details')
            ->where('role', 'seller')
            ->select(
                'id',
                'name',
                'email',
                'mobile_no',
                'country',
                'state',
                'skills',
                'status'
            )
            ->get();
    }

    public static function getSellerCount()
    {
        return self::where('role', 'seller')->count();
    }
}