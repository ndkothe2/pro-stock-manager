<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemLog;
use App\Models\Configuration;

class SystemDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Configurations
        Configuration::updateOrCreate(['key' => 'site_title'], ['value' => 'Pro Stock Manager', 'label' => 'Application Name', 'field_type' => 'text']);
        Configuration::updateOrCreate(['key' => 'admin_email'], ['value' => 'admin@prostock.com', 'label' => 'Primary Administrator Email', 'field_type' => 'text']);
        Configuration::updateOrCreate(['key' => 'system_maintenance'], ['value' => '0', 'label' => 'Maintenance Mode', 'field_type' => 'boolean']);
        Configuration::updateOrCreate(['key' => 'max_upload_size'], ['value' => '2048', 'label' => 'Max Brand Image Upload Size (KB)', 'field_type' => 'text']);

        // 2. Seed Logs (dummy data)
        SystemLog::insert([
            ['user_name' => 'Nikhil Admin', 'action' => 'LOGIN', 'details' => 'Administrator logged into the terminal', 'ip_address' => '127.0.0.1'],
            ['user_name' => 'Nikhil Seller', 'action' => 'LOGIN', 'details' => 'Seller accessed dashboard successfully', 'ip_address' => '127.0.0.1'],
            ['user_name' => 'System', 'action' => 'BACKUP', 'details' => 'Automated database backup completed', 'ip_address' => '::1'],
            ['user_name' => 'Nikhil Admin', 'action' => 'CREATE', 'details' => 'Authorized new seller account: nikhil_new@example.com', 'ip_address' => '127.0.0.1'],
            ['user_name' => 'Nikhil Admin', 'action' => 'UPDATE', 'details' => 'Modified system site title configuration', 'ip_address' => '127.0.0.1'],
            ['user_name' => 'System', 'action' => 'CRON', 'details' => 'Stock status synchronization service executed', 'ip_address' => '::1']
        ]);
    }
}
