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
        Schema::table('sliders', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->after('image'); // 1 = Active, 0 = Inactive
            $table->integer('sort_order')->default(0)->after('is_active'); // Pehle kaun sa aaye ga
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'sort_order']);
        });
    }
};
