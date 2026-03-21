<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_system_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('label')->nullable();
            $table->string('field_type')->default('text'); // text, select, boolean
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_system_configurations');
    }
};
