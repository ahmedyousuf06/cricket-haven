<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider');
            $table->string('provider_ref');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->timestamp('created_at')->useCurrent();
            $table->index('provider_ref');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
