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
    Schema::create('inventarios', function (Blueprint $table) {
        $table->id();
        $table->string('producto');
        $table->decimal('precio_venta', 8, 2);
        $table->decimal('precio_importacion', 8, 2);
        $table->decimal('ganancia', 8, 2);
        $table->timestamps();
    });
}
};
