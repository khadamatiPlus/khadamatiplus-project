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
        Schema::table('banners', function (Blueprint $table) {
            $table->enum('type', ['link', 'category', 'service', 'merchant'])->default('link')->after('image');
            $table->unsignedBigInteger('category_id')->nullable()->after('type');
            $table->unsignedBigInteger('service_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('merchant_id')->nullable()->after('service_id');

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('set null');
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onDelete('set null');
            $table->foreign('merchant_id')
                ->references('id')->on('merchants')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['merchant_id']);

            $table->dropColumn(['type', 'category_id', 'service_id', 'merchant_id']);
        });
    }
};
