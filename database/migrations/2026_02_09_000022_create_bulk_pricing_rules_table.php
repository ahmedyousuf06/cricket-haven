<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->integer('min_qty');
            $table->decimal('price_per_unit', 10, 2);
            $table->unique(['product_variant_id', 'min_qty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_pricing_rules');
    }
};
