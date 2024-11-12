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
        Schema::table('prabayars', function (Blueprint $table) {
            $table->double('price_member')->nullable()->after('price');
            $table->double('price_reseller')->nullable()->after('price_member');
            $table->double('price_agen')->nullable()->after('price_reseller');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prabayars', function (Blueprint $table) {
            $table->dropColumn('price_member');
            $table->dropColumn('price_reseller');
            $table->dropColumn('price_agen');
        });
    }
};
