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
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('notification_type', ['category', 'service', 'informative'])->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['notification_type', 'category_id', 'service_id']);

        });
    }
};
