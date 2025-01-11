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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 300);
            $table->string('phone_number', 20);
            $table->string('email')->nullable();
            $table->string('latitude', 300)->nullable();
            $table->string('longitude', 300)->nullable();
            $table->string('building_number', 300)->nullable();
            $table->string('apartment_number', 300)->nullable();
            $table->string('street_name', 300)->nullable();
            $table->string('floor',150)->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by_id');
            $table->unsignedBigInteger('updated_by_id');
            $table->foreign('created_by_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('updated_by_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
