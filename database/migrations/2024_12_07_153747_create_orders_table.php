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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('cancelled_by_id')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('total_price', 8, 2)->nullable();
            $table->string('longitude');
            $table->string('latitude');
            $table->string('customer_phone');
            $table->string('day');
            $table->string('time');
            $table->string('customer_requested_at')->nullable();
            $table->string('merchant_accepted_at')->nullable();
            $table->string('merchant_arrived_at')->nullable();
            $table->string('merchant_started_trip_at')->nullable();
            $table->string('merchant_on_the_way_at')->nullable();
            $table->string('delivered_at')->nullable();
            $table->string('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->string('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('cancelled_by_id')->references('id')->on('users')->onDelete('cascade');


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
        Schema::dropIfExists('orders');
    }
};
