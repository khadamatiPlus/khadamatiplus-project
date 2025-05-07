<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
        });
    }
};
