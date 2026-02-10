<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_rating_summary', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->primary();
            $table->decimal('avg_rating', 4, 2)->default(0);
            $table->integer('review_count')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_rating_summary');
    }
};
