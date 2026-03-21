<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tbl_customer_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile_no')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('password');
            $table->enum('status', ['0', '1'])->default('0'); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tbl_customer_details');
    }
};
