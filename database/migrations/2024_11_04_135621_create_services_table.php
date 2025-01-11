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
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('title_ar');
            $table->string('price');
            $table->string('new_price')->nullable();
            $table->longText('description');
            $table->longText('description_ar')->nullable();
            $table->string('order')->nullable();
            $table->text('video')->nullable();
            $table->text('main_image')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('merchant_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            $table->foreign('sub_category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            $table->foreign('merchant_id')
                ->references('id')->on('merchants')
                ->onDelete('cascade');
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
        Schema::dropIfExists('services');
    }
};
