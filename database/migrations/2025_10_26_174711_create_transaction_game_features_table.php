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
        Schema::create('transaction_game_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vip_game_streaming_id')->constrained('vip_game_streamings');
            $table->string('invoice')->unique();
            $table->string('trxid');
            $table->string('data_no')->nullable();
            $table->string('data_zone')->nullable();
            $table->string('status');
            $table->text('note')->nullable();
            $table->double('original_price');
            $table->double('selling_price');
            $table->double('margin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_game_features');
    }
};
