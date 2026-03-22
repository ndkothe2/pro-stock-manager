<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tbl_wishlist_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('tbl_customer_details')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('tbl_product_details')->onDelete('cascade');
            $table->unique(['customer_id', 'product_id']); // Ensure only one entry per user-product
        });
    }

    public function down(): void {
        Schema::dropIfExists('tbl_wishlist_details');
    }
};
