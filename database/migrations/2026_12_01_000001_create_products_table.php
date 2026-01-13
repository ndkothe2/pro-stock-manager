<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tbl_product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_admin_details')->onDelete('cascade');
            $table->string('product_name');
            $table->text('product_description');
            $table->enum('delete_status', ['0', '1'])->default('0'); 
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tbl_product_details');
    }
};