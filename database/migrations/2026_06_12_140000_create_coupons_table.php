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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            // Discount Details
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('minimum_order_amount', 10, 2)->nullable()->default(0);
            $table->decimal('maximum_discount_amount', 10, 2)->nullable();
            
            // Usage Limits
            $table->integer('usage_limit')->nullable()->comment('null for unlimited');
            $table->integer('used_count')->default(0);
            
            // Validity Period
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Foreign keys and timestamps
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
