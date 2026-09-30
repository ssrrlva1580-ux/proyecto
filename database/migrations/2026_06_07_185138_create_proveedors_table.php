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
    Schema::create('proveedores', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_completo'); // Casillero para el nombre
        $table->string('nombre_empresa');  // Casillero para la empresa
        $table->string('contacto');        // Casillero para teléfono/correo
        $table->timestamps();
    });
}
};
