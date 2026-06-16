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
            $table->json('variants')->nullable()->after('free_revisions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_services', function (Blueprint $table) {
            $table->dropColumn('variants');
        });
    }
};
