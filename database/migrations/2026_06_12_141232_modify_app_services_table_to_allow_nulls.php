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
        Schema::table('app_services', function (Blueprint $table) {
            $table->decimal('discount', 5, 2)->nullable()->change();
            $table->string('delivery_time_unit')->nullable()->change();
            $table->integer('free_revisions')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_services', function (Blueprint $table) {
            $table->decimal('discount', 5, 2)->default(0)->change();
            $table->string('delivery_time_unit')->default('days')->change();
            $table->integer('free_revisions')->default(0)->change();
        });
    }
};
