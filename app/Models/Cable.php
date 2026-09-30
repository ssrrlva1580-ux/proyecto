<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cable extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'precio_compra_caja',
        'precio_venta_caja',
        'precio_media_docena',
        'precio_una_docena',
    ];

    // Relación inversa con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}