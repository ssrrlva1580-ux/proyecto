<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'jornada_id',
        'producto_id',
        'nombre_producto',
        'marca',
        'categoria',
        'cantidad',
        'precio_compra',
        'precio_venta',
        'subtotal_venta',
        'subtotal_compra',
        'ganancia',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'subtotal_venta' => 'decimal:2',
        'subtotal_compra' => 'decimal:2',
        'ganancia' => 'decimal:2',
    ];

    /**
     * Relación: Una venta pertenece a una jornada
     */
    public function jornada()
    {
        return $this->belongsTo(Jornada::class);
    }

    /**
     * Relación: Una venta pertenece a un producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Calcular ganancia automáticamente antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($venta) {
            $venta->subtotal_venta = $venta->cantidad * $venta->precio_venta;
            $venta->subtotal_compra = $venta->cantidad * $venta->precio_compra;
            $venta->ganancia = $venta->subtotal_venta - $venta->subtotal_compra;
        });

        // Recalcular totales de la jornada después de guardar
        static::saved(function ($venta) {
            if ($venta->jornada) {
                $venta->jornada->recalcularTotales();
            }
        });

        // Recalcular totales de la jornada después de eliminar
        static::deleted(function ($venta) {
            if ($venta->jornada) {
                $venta->jornada->recalcularTotales();
            }
        });
    }
}