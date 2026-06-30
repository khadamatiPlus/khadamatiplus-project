<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['merchant_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable()->change();
            $table->unsignedBigInteger('merchant_id')->nullable()->change();
            $table->unsignedBigInteger('app_service_id')->nullable()->after('service_id');
            $table->json('selected_variants')->nullable()->after('app_service_id');

            $table->foreign('app_service_id')
                ->references('id')
                ->on('app_services')
                ->nullOnDelete();
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->nullOnDelete();
            $table->foreign('merchant_id')
                ->references('id')
                ->on('merchants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['app_service_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['merchant_id']);
            $table->dropColumn(['app_service_id', 'selected_variants']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable(false)->change();
            $table->unsignedBigInteger('merchant_id')->nullable(false)->change();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
        });
    }
};
