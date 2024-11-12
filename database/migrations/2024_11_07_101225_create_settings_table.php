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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('val1')->nullable();
            $table->text('val2')->nullable();
            $table->text('val3')->nullable();
            $table->text('val4')->nullable();
            $table->text('val5')->nullable();
            $table->text('val6')->nullable();
            $table->text('val7')->nullable();
            $table->text('val8')->nullable();
            $table->text('val9')->nullable();
            $table->text('val10')->nullable();
            $table->text('val11')->nullable();
            $table->text('val12')->nullable();
            $table->text('val13')->nullable();
            $table->text('val14')->nullable();
            $table->text('val15')->nullable();
            $table->text('val16')->nullable();
            $table->text('val17')->nullable();
            $table->text('val18')->nullable();
            $table->text('val19')->nullable();
            $table->text('val20')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
