<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria');
            $table->string('marca')->nullable();
            $table->string('tipo_medida')->nullable();
            $table->integer('cantidad')->default(0);
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->decimal('precio_unidad', 10, 2)->default(0);
            $table->decimal('precio_media_docena', 10, 2)->default(0);  // ✅ AGREGADO
            $table->decimal('precio_una_docena', 10, 2)->default(0);    // ✅ AGREGADO
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};