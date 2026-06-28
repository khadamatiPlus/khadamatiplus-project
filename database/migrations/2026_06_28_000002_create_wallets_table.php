<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->string('owner_type');
                $table->string('owner_id');
                $table->string('type')->default('default');
                $table->decimal('balance', 12, 2)->default(0.00);
                $table->timestamps();
                $table->softDeletes();
                $table->index(['owner_type', 'owner_id', 'type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
