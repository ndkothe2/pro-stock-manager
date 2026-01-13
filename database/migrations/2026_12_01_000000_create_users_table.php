<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tbl_admin_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile_no');
            $table->string('country');
            $table->string('state');
            $table->json('skills');
            $table->string('password');
            $table->enum('role', ['admin', 'seller'])->default('seller');
            $table->enum('status', ['0', '1'])->default('0'); 
            
            $table->integer('delete_status')->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tbl_admin_details');
    }
};