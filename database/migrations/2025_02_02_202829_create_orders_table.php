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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('status')->default('0'); // 0 - Aberto, 1 - Em andamento, 2 - Concluído
            $table->string('description')->required();
            $table->decimal('total_parts_price', 10, 2)->default(0);
            $table->decimal('service_price', 10, 2)->default(0);
            $table->decimal('final_total', 10, 2)->default(0);
            $table->json('images')->nullable();

            // $table->json('services')->nullable(); //tabela a parte dos servicos
            // $table->json('products')->nullable(); //tabela a parte dos produtos

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
