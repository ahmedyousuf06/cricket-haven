<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('product_bundles')->cascadeOnDelete();
            $table->string('path');
            $table->integer('sort_order')->default(0);
            $table->index(['bundle_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_images');
    }
};
