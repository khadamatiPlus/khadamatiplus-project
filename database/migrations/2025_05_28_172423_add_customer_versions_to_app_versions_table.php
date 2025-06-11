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
        Schema::table('app_versions', function (Blueprint $table) {
            $table->string('customer_version_android')->default('1.0.0')->after('current_version_huawei');
            $table->string('customer_version_ios')->default('1.0.0')->after('customer_version_android');
            $table->string('customer_version_huawei')->default('1.0.0')->after('customer_version_ios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_versions', function (Blueprint $table) {
            $table->dropColumn([
                'customer_version_android',
                'customer_version_ios',
                'customer_version_huawei'
            ]);
        });
    }
};
