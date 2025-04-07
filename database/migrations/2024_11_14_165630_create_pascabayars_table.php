<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pascabayars', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->string('brand');
            $table->string('seller_name');
            $table->double('admin');
            $table->double('commission');
            $table->string('buyer_sku_code');
            $table->string('buyer_product_status');
            $table->integer('seller_product_status');
            $table->string('provider')->default('digiflazz');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pascabayars');
    }
};
