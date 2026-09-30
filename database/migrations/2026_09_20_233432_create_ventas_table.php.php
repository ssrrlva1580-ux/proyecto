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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jornada_id')->constrained('jornadas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            
            // Datos copiados del producto al momento de la venta (histórico)
            $table->string('nombre_producto');
            $table->string('marca')->nullable();
            $table->string('categoria');
            
            // Cantidades y precios
            $table->integer('cantidad');
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            
            // Cálculos automáticos
            $table->decimal('subtotal_venta', 10, 2);
            $table->decimal('subtotal_compra', 10, 2);
            $table->decimal('ganancia', 10, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};