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
        Schema::create('jornadas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique(); // Fecha única (un día = una jornada)
            $table->integer('mes');          // Mes (1-12)
            $table->integer('anio');         // Año (2026, 2027, etc.)
            $table->decimal('total_ventas', 12, 2)->default(0);
            $table->decimal('total_ganancia', 12, 2)->default(0);
            $table->integer('total_productos_vendidos')->default(0);
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jornadas');
    }
};