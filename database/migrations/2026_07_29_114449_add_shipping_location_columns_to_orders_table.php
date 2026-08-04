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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->after('shipping_address');
            $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
            $table->unsignedBigInteger('city_id')->nullable()->after('state_id');
            $table->string('postal_code')->nullable()->after('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['country_id', 'state_id', 'city_id', 'postal_code']);
        });
    }
};
