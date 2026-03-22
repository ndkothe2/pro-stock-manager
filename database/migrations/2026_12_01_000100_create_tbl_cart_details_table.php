<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tbl_cart_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('tbl_customer_details')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('tbl_product_details')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tbl_cart_details');
    }
};
