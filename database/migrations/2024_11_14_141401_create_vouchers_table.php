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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('target_id')->nullable();
            $table->string('invoice');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->integer('qty');
            $table->integer('value');
            $table->enum('type_target', ['Public', 'Private']);
            $table->enum('type_voucher', ['Saldo', 'Discount Harga', 'Discount Persen']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
