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
        Schema::table('admin_stores', function (Blueprint $table) {
            Schema::table('admin_stores', function (Blueprint $table) {
                $table->boolean('is_sliders')->default(1)->after('is_title');
                $table->boolean('show_mid_banners')->default(1)->after('is_sliders');
                $table->boolean('show_featured_banner')->default(1)->after('show_mid_banners');
                $table->boolean('show_brand_banner')->default(1)->after('show_featured_banner');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_stores', function (Blueprint $table) {
            $table->dropColumn([
                'is_sliders',
                'show_mid_banners',
                'show_featured_banner',
                'show_brand_banner'
            ]);
        });
    }
};
