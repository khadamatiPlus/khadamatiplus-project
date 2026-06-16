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
        Schema::create('app_services', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            
            // Media
            $table->text('images')->nullable(); // JSON array of image paths
            $table->string('video_url')->nullable();
            
            // Pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->string('currency', 10)->default('JOD');
            $table->string('price_type')->default('fixed'); // fixed, hourly, starts_from, by_agreement
            $table->decimal('discount', 5, 2)->nullable()->default(0);
            $table->integer('delivery_time')->nullable();
            $table->string('delivery_time_unit')->nullable()->default('days'); // days, hours, weeks
            $table->integer('free_revisions')->nullable()->default(0);
            
            // Requirements
            $table->text('customer_requirements')->nullable();
            $table->boolean('requirements_mandatory')->default(true);
            
            // SEO & Tags
            $table->text('tags')->nullable(); // JSON array
            $table->string('seo_description', 160)->nullable();
            
            // Advanced Settings
            $table->string('language')->default('arabic'); // arabic, english, both
            $table->string('scope')->default('local'); // local, global, online_only
            $table->text('availability_days')->nullable(); // JSON array of days
            $table->integer('max_concurrent_orders')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_online')->default(true);
            
            // Status
            $table->string('status')->default('active'); // active, draft, inactive, scheduled
            $table->string('visibility')->default('all'); // all, registered_only, specific_customers
            
            // Foreign keys and timestamps
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_services');
    }
};
