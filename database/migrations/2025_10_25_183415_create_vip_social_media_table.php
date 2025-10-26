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
        Schema::create('vip_social_media', function (Blueprint $table) {
            $table->id();
            $table->integer('id_vipayment');
            $table->string('category');
            $table->integer('min');
            $table->integer('max');
            $table->string('name');
            $table->text('note');
            $table->double('price');
            $table->double('price_member');
            $table->double('price_agen');
            $table->double('price_reseller');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vip_social_media');
    }
};
