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
        Schema::create('highlights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('title_ar');
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('link')->nullable();
            $table->text('image')->nullable();
            $table->boolean('status')->default(false);
            $table->enum('type', ['none','link', 'category', 'service', 'merchant'])->default('none');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('set null');
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onDelete('set null');
            $table->foreign('merchant_id')
                ->references('id')->on('merchants')
                ->onDelete('set null');

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
        Schema::dropIfExists('highlights');
    }
};
