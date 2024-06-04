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
            $table->text('description');
            $table->text('s1');
            $table->text('s2');
            $table->text('s3');
            $table->text('s4');
            $table->text('s5');
            $table->text('s6');
            $table->text('s7');
            $table->text('s8');
            $table->text('s9');
            $table->text('s10');
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
