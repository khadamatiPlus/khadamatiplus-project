<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
                $table->string('direction');
                $table->decimal('amount', 12, 2);
                $table->decimal('running_balance', 12, 2);
                $table->text('description')->nullable();
                $table->string('reference_type')->nullable();
                $table->string('reference_id')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
