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
        Schema::create('vip_game_streamings', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('game');
            $table->string('name');
            $table->double('price');
            $table->double('price_member');
            $table->double('price_agen');
            $table->double('price_reseller');
            $table->string('server');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vip_game_streamings');
    }
};
