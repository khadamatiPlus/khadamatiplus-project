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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'is_hidden_from_api')) {
                $table->boolean('is_hidden_from_api')->default(false)->after('is_featured');
            }
        });

        Schema::table('app_services', function (Blueprint $table) {
            if (!Schema::hasColumn('app_services', 'is_hidden_from_api')) {
                $table->boolean('is_hidden_from_api')->default(false)->after('is_online');
            }
        });

        Schema::table('merchants', function (Blueprint $table) {
            if (!Schema::hasColumn('merchants', 'is_hidden_from_api')) {
                $table->boolean('is_hidden_from_api')->default(false)->after('is_verified');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_hidden_from_api')) {
                $table->dropColumn('is_hidden_from_api');
            }
        });

        Schema::table('app_services', function (Blueprint $table) {
            if (Schema::hasColumn('app_services', 'is_hidden_from_api')) {
                $table->dropColumn('is_hidden_from_api');
            }
        });

        Schema::table('merchants', function (Blueprint $table) {
            if (Schema::hasColumn('merchants', 'is_hidden_from_api')) {
                $table->dropColumn('is_hidden_from_api');
            }
        });
    }
};
