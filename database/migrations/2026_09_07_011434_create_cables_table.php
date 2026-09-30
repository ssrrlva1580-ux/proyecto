<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('cables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('producto_id'); // Llave foránea hacia productos
        $table->decimal('precio_compra_caja', 10, 2)->default(0);
        $table->decimal('precio_venta_caja', 10, 2)->default(0);
        $table->decimal('precio_media_docena', 10, 2)->default(0);
        $table->decimal('precio_una_docena', 10, 2)->default(0);
        $table->timestamps();

        // Relación
        $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
    });
}
    public function down(): void
    {
        Schema::dropIfExists('cables');
    }
};
