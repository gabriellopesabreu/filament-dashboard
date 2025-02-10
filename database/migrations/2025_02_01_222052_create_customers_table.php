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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('last_name')->required();
            $table->string('phone')->required();
            $table->string('cpf')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('BR')->nullable();
            $table->string('state')->default('ES')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
