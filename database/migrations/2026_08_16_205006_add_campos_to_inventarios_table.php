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
        Schema::table('inventarios', function (Blueprint $table) {
            // ✅ Verificar si la columna no existe antes de agregarla
            if (!Schema::hasColumn('inventarios', 'cantidad_vendida')) {
                $table->integer('cantidad_vendida')->default(0);
            }
            if (!Schema::hasColumn('inventarios', 'precio_venta_total')) {
                $table->decimal('precio_venta_total', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('inventarios', 'precio_importacion_total')) {
                $table->decimal('precio_importacion_total', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('inventarios', 'ganancia')) {
                $table->decimal('ganancia', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('inventarios', 'fecha_venta')) {
                $table->date('fecha_venta')->nullable();
            }
            if (!Schema::hasColumn('inventarios', 'producto_id')) {
                $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropColumn([
                'cantidad_vendida',
                'precio_venta_total',
                'precio_importacion_total',
                'ganancia',
                'fecha_venta'
            ]);
            
            // Solo eliminar la clave foránea si existe
            if (Schema::hasColumn('inventarios', 'producto_id')) {
                $table->dropForeign(['producto_id']);
                $table->dropColumn('producto_id');
            }
        });
    }
};