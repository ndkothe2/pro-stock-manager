<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_system_logs';
    protected $fillable = ['user_name', 'action', 'details', 'ip_address'];
    public $timestamps = false; // We use created_at via database default
}
