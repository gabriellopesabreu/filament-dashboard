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
        Schema::create('order_part', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
            $table->decimal('unit_price', 10, 2); // Preço unitário no momento da compra
            $table->decimal('total_price', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_part');
    }
};
