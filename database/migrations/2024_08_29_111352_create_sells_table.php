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
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2); // Definir 'price' como decimal en lugar de string
            $table->string('title', 100);
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Relación con clientes
            $table->foreignId('comission_id')->constrained()->onDelete('cascade'); // Relación con comisiones
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuarios
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells');
    }
};
